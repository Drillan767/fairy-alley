<?php

namespace App\Http\Controllers;

use App\Events\FirstContactCreated;
use App\Http\Requests\FirstContactRequest;
use App\Models\FirstContact;
use App\Models\Lesson;
use App\Models\Subscription;
use App\Models\User;
use App\Models\YearData;

class FirstContactController extends Controller
{
    public function create()
    {
        return view('register', [
            'lessons' => Lesson::all('id', 'title')->orderBy('title'),
        ]);
    }

    public function store(FirstContactRequest $request)
    {
        $user = new User();
        foreach ($this->columns()['user'] as $field) {
            $user->$field = $request->get($field);
        }

        // User won't be able to login anyway.
        $user->password = '';
        $user->lesson_id = $request->get('lesson');

        $user->save();
        $user->assignRole('first_contact');

        $firstContact = new FirstContact();
        foreach ($this->columns()['first_contact'] as $field) {
            $firstContact->$field = $request->get($field);
        }

        $user->firstContactData()->save($firstContact);

        $subscription = new Subscription();
        $subscription->lesson_id = $request->get('lesson');
        $subscription->status = Subscription::PENDING;
        $subscription->invites = [];
        $subscription->feedback = '';

        $user->subscription()->save($subscription);

        $yearData = new YearData();
        foreach (['health_issues', 'current_health_issues', 'medical_treatment'] as $hFields) {
            if ($request->get($hFields) !== null && $request->get($hFields) !== '') {
                $yearData->health_data = $request->get($hFields) . "\n\n";
            }
        }

        $user->yearDatas()->save($yearData);

        event(new FirstContactCreated($user));

        return redirect()->back()->with('success', "Votre demande d'inscription a été soumise avec succès. Nous reviendrons vers vous dans les plus brefs délais.");
    }

    private function columns(): array
    {
        return [
            'user' => [
                'firstname',
                'lastname',
                'email',
                'birthday',
                'gender',
                'phone',
                'pro',
                'address1',
                'address2',
                'zipcode',
                'city',
                'other_data',
            ],
            'first_contact' => [
                'family_situation',
                'work',
                'nb_children',
                'medical_treatment',
                'sports',
                'objectives',
            ],
        ];
    }
}
