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
}
