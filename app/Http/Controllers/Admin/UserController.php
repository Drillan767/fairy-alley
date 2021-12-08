<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionValidationRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use App\Models\{Lesson, Service, Subscription, User};
use App\Services\SubscriptionHandler;
use Illuminate\Http\RedirectResponse;
use Inertia\{Inertia, Response};

class UserController extends Controller
{
    public function __construct(protected SubscriptionHandler $subscriptionHandler)
    {}

    public function index(): Response
    {
        $lessons = Lesson::all('id', 'title');
        $users = User::with('lesson')->get();

        return Inertia::render('Admin/Users/List', compact('users', 'lessons'));
    }

    public function show(User $utilisateur)
    {
        $services = Service::all(['id', 'title']);
        $utilisateur->load('currentYearData.file', 'lesson', 'subscription', 'suggestions');
        return Inertia::render('Admin/Users/Show', [
            'currentUser' => $utilisateur,
            'services' => $services,
        ]);
    }

    public function edit(User $utilisateur)
    {
        return Inertia::render('Admin/Users/Edit',
            [
                'subscriber' => $utilisateur->load('subscription.lesson', 'yearDatas')
            ]
        );
    }

    public function update(UserUpdateRequest $request, User $utilisateur)
    {
        $fields = [
            'firstname',
            'lastname',
            'email',
            'gender',
            'birthday',
            'other_data',
            'address1',
            'address2',
            'zipcode',
            'city',
            'phone',
            'pro',
        ];

        foreach ($fields as $field) {
            $utilisateur->$field = $request->get($field);
        }

        $utilisateur->save();

        $utilisateur->suggestions()->sync($request->get('suggestions'));

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur mit à jour avec succès.');
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

    public function redirectHome(Request $request): \Illuminate\Http\Response|RedirectResponse
    {
        if ($request->ajax()) {
            return Inertia::location(route('landing'));
        }

        return redirect()->route('landing');
    }
}
