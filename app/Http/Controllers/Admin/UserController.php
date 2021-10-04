<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionValidation;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionMissingElements;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function subscribed(): Response
    {
        $users = User::whereNotNull('lesson_id')
            ->role('subscriber')
            ->get();

        return Inertia::render('Admin/Users/List', compact('users'));
    }

    public function preSubscribed(): Response
    {
        $users = User::role('subscriber')
            ->with('subscription', function($query) {
                $query->whereIn('status', [
                    Subscription::PENDING,
                    Subscription::NEEDS_INFOS,
                    Subscription::AWAITING_PAYMENT
                ]);
            })
            ->where('lesson_id', null)
            ->get();

        return Inertia::render('Admin/Users/List', compact('users'));
    }

    public function subscribing(User $user): Response
    {
        return Inertia::render('Admin/Users/Subscribing',
            [
                'subscriber' => $user->load(
                    'subscription.lesson',
                    'currentYearData.file'
                )
            ]
        );
    }

    public function subscribe(SubscriptionValidation $request)
    {
        $user = User::find($request->get('id'));
        foreach(['firstname', 'lastname', 'birthday', 'email', 'gender', 'phone', 'pro', 'address1', 'address2', 'zipcode', 'city'] as $field) {
            $user->$field = $request->get($field);
        }
        $user->save();

        $args = [];

        switch ($request->get('decision')) {
            case 'missing':

                $status = Subscription::NEEDS_INFOS;
                break;

            case 'payment':
                break;

            case 'accepted':
                break;
        }

        dd($request);

        $user->notify(new SubscriptionMissingElements($args));


    }

    public function edit(User $utilisateur)
    {
        return Inertia::render('Admin/Users/Edit',
            [
                'subscriber' => $utilisateur->load(
                'subscription.lesson',
                'yearDatas'
                )
            ]
        );
    }
}
