<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionValidationRequest;
use App\Models\Invite;
use App\Models\Lesson;
use App\Models\Subscription;
use App\Models\User;
use App\Models\YearData;
use App\Notifications\SubscriptionMissingElements;
use App\Services\SubscriptionHandler;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(protected SubscriptionHandler$subscriptionHandler)
    {}

    public function subscribed(): Response
    {
        $users = User::whereNotNull('lesson_id')
            ->role('subscriber')
            ->with('lesson')
            ->whereHas('subscription', function ($query) {
                $query->where('status', Subscription::VALIDATED);
            })
            ->get();

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
                'subscriber' => $user->load(
                    'subscription.lesson',
                    'currentYearData.file'
                )
            ]
        );
    }

    public function subscribe(SubscriptionValidationRequest $request)
    {
        list($route, $type, $message) = $this->subscriptionHandler->validate($request);
        return redirect()->route($route)->with($type, $message);
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
