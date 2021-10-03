<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionValidation;
use App\Models\Subscription;
use App\Models\User;
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
        dd($request);
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
