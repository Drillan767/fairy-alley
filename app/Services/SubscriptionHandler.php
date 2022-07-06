<?php

namespace App\Services;

use App\Notifications\RenewalUpdated;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\{RenewalRequest, SubscriptionRequest};
use App\Http\Requests\SubscriptionValidationRequest;
use App\Models\{Invite, Media, Subscription, User, YearData};
use App\Notifications\SubscriptionAccepted;
use App\Notifications\SubscriptionMissingElements;
use Illuminate\Support\Facades\Storage;
use Spatie\Valuestore\Valuestore;

class SubscriptionHandler
{
    public function __construct(protected FileHandler $fileHandler)
    {
    }

    public function create(SubscriptionRequest|RenewalRequest $request)
    {

        /** @var User $user */
        $user = $request->user();
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $renewalData = Valuestore::make(storage_path('app/renewal.json'));
        $renewalData->put([
            'user_' . auth()->id() => [
                'lesson_choices' => [$request->get('schedule1'), $request->get('schedule2')],
                'admin_decision' => null,
                'feedback' => '',
                'payment' => $request->get('payment_method'),
                'invites' => $request->get('invites') ?? [],
            ]
        ]);

        $yearData = new YearData();
        $yearData->user_id = $user->id;
        $yearData->health_data = $request->get('health_data');
        $yearData->reply_transmitted_via = 'internet';
        $yearData->last_year_class = $user->lesson->title;
        $yearData->pre_registration_signature = now();

        if ($request->get('payment_method') === 'full') {
            $yearData->total = $settings->get('price_full');
        } else {
            $yearData->total = $settings->get('price_quarterly') * 3;
            $yearData->payments = [
                ['amount' => $settings->get('price_quarterly'), 'paid_at' => ''],
                ['amount' => $settings->get('price_quarterly'), 'paid_at' => ''],
                ['amount' => $settings->get('price_quarterly'), 'paid_at' => ''],
            ];
        }

        $yearData->save();

        if ($request->hasFile('medical_certificate')) {
            $file = $request->file('medical_certificate');
            $yearData->save();

            $media = new Media([
                'title' => $file->getClientOriginalName(),
                'url' => Storage::disk('s3')->putFileAs("user/$user->id", $file, $file->getClientOriginalName()),
            ]);

            $yearData->file()->save($media);
        }

        $user->resubscription_status = Subscription::PENDING;
        $user->save();
    }

    public function update(SubscriptionRequest|RenewalRequest $request)
    {
        $user = User::with('currentYearData.file', 'subscription')->find(auth()->id());
        $user->subscription->update([
            'status' => Subscription::PENDING,
            'invites' => $request->get('invites') ?? [],
        ]);

        $yearData = $user->currentYearData;
        $yearData->health_data = $request->get('health_data');
        $yearData->save();

        if ($request->hasFile('medical_certificate')) {
            $this->fileHandler->uploadOrReplace($request->file('medical_certificate'), $yearData, $user);
        }

        Notification::sendNow(User::role('administrator')->get(), new RenewalUpdated($user->full_name, $user->id));
    }

    public function validate(SubscriptionValidationRequest $request): array
    {
        $user = User::find($request->get('id'));
        foreach (['firstname', 'lastname', 'birthday', 'email', 'gender', 'phone', 'pro', 'address1', 'address2', 'zipcode', 'city'] as $field) {
            $user->$field = $request->get($field);
        }
        $user->save();

        $subscription = Subscription::find($request->get('subscription')['id']);

        $yearData = YearData::find($request->get('current_year_data')['id']);
        $yearData->payments = $request->get('payments');
        $yearData->pre_registration_signature = $request->get('pre_registration_signature');

        if ($request->hasFile('medical_certificate')) {
            $this->fileHandler->uploadOrReplace($request->file('medical_certificate'), $yearData, $user);

            // Send notification saying an update was made to the subscription
        }

        if ($request->get('decision') === 'missing') {
            $subscription->status = Subscription::NEEDS_INFOS;
            $subscription->feedback = $request->get('feedback');
            $subscription->save();

            if ($yearData->observations === null || $yearData->observations === '') {
                $yearData->observations = 'Motif de refus : "' . $request->get('feedback') . '"';
            }

            $user->notify(new SubscriptionMissingElements($subscription->toArray()));
            $response = ['utilisateurs.presubscribed', 'success', "Le statut de l'inscription a bien été mis à jour."];
        } elseif ($request->get('decision') === 'accepted') {
            $user->lesson_id = $subscription->lesson_id;
            $user->save();
            $subscription->status = Subscription::VALIDATED;
            $user->notify(new SubscriptionAccepted($subscription->load('lesson')->toArray()));

            if ($subscription->invites) {
                foreach ($subscription->invites as $invite) {
                    Invite::create(
                        array_merge($invite, ['user_id' => $user->id])
                    );
                }
            }

            $subscription->invites = null;
            $response = ['utilisateurs.presubscribed', 'success', "L'inscription a bien été validée."];
        } else {
            $response = ['utilisateurs.presubscribed', 'message', 'Ahein'];
        }

        $subscription->save();
        $yearData->save();

        return $response;
    }
}
