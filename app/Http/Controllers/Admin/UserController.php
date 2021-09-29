<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            ->with('subscription')
            ->where('lesson_id', null)
            ->where('group_id', null)
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

    public function subscribe()
    {

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
