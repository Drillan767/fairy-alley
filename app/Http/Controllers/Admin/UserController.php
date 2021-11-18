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

    public function index(Request $request): Response
    {
        $lessons = Lesson::all('id', 'title');
        $users = [];


        if (!empty($request->all())) {
            // dd($request->all());
            switch ($request->get('type')) {
                case 'filter':
                    $column = $request->get('column');
                    // TODO: handle subscription status + add filter handling at the same time.
                    if (isset($column['lesson'])) {
                        if ($column['lesson'] === '0') {
                            $users = User::whereNull('lesson_id')->with('lesson')->get();
                        } else {
                            $users = User::where('lesson_id', $column['lesson'])->with('lesson')->get();
                        }
                    }
                    break;

                case 'sort':
                    // dd($request->all());
                    $users = User::with('lesson')->get();
                    break;
            }
        } else {
            $users = User::with('lesson')->get();
        }




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

    public function destroy(User $user)
    {
        if (auth()->user()->role === 'administrator') {
            $user->yearDatas->each->delete();
            $user->files->each->delete();
            $user->subscription()->delete();
            $user->roles()->detach();
            $user->delete();
        } else {
            abort(403);
        }
    }
}
