<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionValidationRequest;
use Illuminate\Http\Request;
use App\Models\{Lesson, Subscription, User};
use App\Services\SubscriptionHandler;
use Illuminate\Http\RedirectResponse;
use Inertia\{Inertia, Response};

class UserController extends Controller
{
    public function __construct(protected SubscriptionHandler $subscriptionHandler)
    {}

    public function show(User $utilisateur)
    {
        $utilisateur->load('currentYearData.file', 'lesson', 'subscription');
        return Inertia::render('Admin/Users/Show', ['currentUser' => $utilisateur]);
    }

    public function index(): Response
    {
        $users = User::with('roles', 'lesson')->get();
        $lessons = Lesson::all('id', 'title');

        return Inertia::render('Admin/Users/List', compact('users', 'lessons'));
    }

    public function preSubscribed(): Response
    {
        $users = User::role('subscriber')
            ->has('subscription')
            ->with('subscription', function($query) {
                $query->whereIn('status', [
                    Subscription::PENDING,
                    Subscription::NEEDS_INFOS,
                    Subscription::AWAITING_PAYMENT
                ]);
            })
            ->where('lesson_id', null)
            ->get();

        return Inertia::render('Admin/Users/Presubscribed', compact('users'));
    }

    public function subscribing(User $user): Response
    {
        return Inertia::render('Admin/Users/Subscribing',
            [
                'subscriber' => $user->load('subscription.lesson','currentYearData.file')
            ]
        );
    }

    public function subscribe(SubscriptionValidationRequest $request): RedirectResponse
    {
        list($route, $type, $message) = $this->subscriptionHandler->validate($request);
        return redirect()->route($route)->with($type, $message);
    }

    public function edit(User $utilisateur)
    {
        return Inertia::render('Admin/Users/Edit',
            [
                'subscriber' => $utilisateur->load('subscription.lesson', 'yearDatas')
            ]
        );
    }

    public function redirectHome(Request $request): \Illuminate\Http\Response|RedirectResponse
    {
        if ($request->ajax()) {
            return Inertia::location(route('landing'));
        }

        return redirect()->route('landing');
    }
}
