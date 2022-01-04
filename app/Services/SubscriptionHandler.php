<?php

namespace App\Services;

use App\Notifications\SubscriptionAccepted;
use App\Http\Requests\{SubscriptionRequest, SubscriptionValidationRequest};
use App\Notifications\SubscriptionMissingElements;
use App\Models\{Invite, Media, Subscription, User, YearData};
use Illuminate\Support\Facades\Storage;

class SubscriptionHandler
{
    public function __construct(protected FileHandler $fileHandler)
    {}
    public function create(SubscriptionRequest $request)
    {
        $user_id = $request->get('user_id');
        $yearData = new YearData();
        $yearData->user_id = $user_id;
        $yearData->health_data = $request->get('health_data');
        $yearData->reply_transmitted_via = 'internet';

        $yearData->save();

        if ($request->hasFile('medical_certificate')) {
            $file = $request->file('medical_certificate');
            $yearData->save();

            $media = new Media([
                'title' => $file->getClientOriginalName(),
                'url' => Storage::disk('s3')->putFileAs("user/{$user_id}", $file, $file->getClientOriginalName()),
            ]);

            $yearData->file()->save($media);
        }

        Subscription::create([
            'status' => Subscription::PENDING,
            'user_id' => $user_id,
            'lesson_id' => $request->get('lesson_id'),
            'selected_time' => $request->get('schedule_choice1'),
            'fallback_time' => $request->get('schedule_choice2'),
            'invites' => $request->get('invites') ?? [],
        ]);
    }

    public function update(SubscriptionRequest $request)
    {
        $user = User::with('currentYearData.file', 'subscription')->find($request->get('user_id'));
        $user->subscription->update([
            'status' => Subscription::PENDING,
            'selected_time' => $request->get('schedule_choice1'),
            'fallback_time' => $request->get('schedule_choice2'),
            'invites' => $request->get('invites') ?? [],
        ]);

        $yearData = $user->currentYearData;
        $yearData->health_data = $request->get('health_data');
        $yearData->save();

        if ($request->hasFile('medical_certificate')) {
            $this->fileHandler->uploadOrReplace($request->file('medical_certificate'), $yearData, $user);

            // Send notification saying an update was made to the subscription
        }
    }

    public function validate(SubscriptionValidationRequest $request): array
    {
        $user = User::find($request->get('id'));
        foreach(['firstname', 'lastname', 'birthday', 'email', 'gender', 'phone', 'pro', 'address1', 'address2', 'zipcode', 'city'] as $field) {
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
            $subscription->selected_time = $request->get('selected_time');
            $subscription->fallback_time = null;
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
