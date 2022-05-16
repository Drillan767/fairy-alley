<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserRoleChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\FirstContactRequest;
use App\Http\Requests\SubscriptionValidationRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Jobs\SendLessonChanged;
use App\Models\Lesson;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use App\Models\YearData;
use App\Services\FirstContactHandler;
use App\Services\SubscriptionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(protected SubscriptionHandler $subscriptionHandler)
    {
    }

    public function index(): Response
    {
        $roles = config('roles');
        $lessons = Lesson::orderBy('title')->get(['id', 'title']);
        $users = User::with('subscription.lesson')->get();

        return Inertia::render('Admin/Users/List', compact('users', 'lessons', 'roles'));
    }

    public function create(): Response
    {
        $lessons = Lesson::orderBy('title')->get(['id', 'title']);

        return Inertia::render('Admin/Users/Create', compact('lessons'));
    }

    public function store(FirstContactRequest $request, FirstContactHandler $firstContactHandler): RedirectResponse
    {
        $firstContactHandler->store($request);
        return redirect()->route('utilisateurs.index')->with('success', "L'utilisateur a été créé et un mail de création de mot de passe lui a été envoyé");
    }

    public function show(User $utilisateur): Response
    {
        $utilisateur->load('currentYearData.file', 'lesson', 'subscription', 'suggestions', 'firstContactData');
        $lessons = Lesson::all('id', 'title')->mapWithKeys(fn ($l) => [$l->id => $l->title]);
        $services = Service::orderBy('title')
            ->get(['id', 'title'])
            ->map(fn ($service) => ['value' => $service->id, 'title' => $service->title]);

        $roles = config('roles');

        return Inertia::render('Admin/Users/Show', [
            'currentUser' => $utilisateur,
            'services' => $services,
            'lessons' => $lessons,
            'roles' => $roles,
        ]);
    }

    public function changeLesson(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user' => ['required', 'exists:users,id'],
            'lid' => ['required', 'exists:lessons,id'],
        ]);

        $user = User::with('subscription', 'lesson')->find($validated['user']);

        if ($validated['lid'] !== $user->lesson_id) {
            $user->lesson_id = $validated['lid'];
            $user->save();
            $user->subscription->update(['lesson_id' => $validated['lid']]);

            SendLessonChanged::dispatchAfterResponse($user, $validated['lid']);

            return redirect()->back()->with('success', 'Le cours a bien été changé.');
        }
        else {
            return redirect()->back()->with('error', 'Le cours sélectionné est le même que celui en cours.');
        }
    }

    public function changeRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::select('id')->find($validated['user']);
        $user->syncRoles([$validated['role']]);
        event(new UserRoleChanged($user, $validated['role']));

        return redirect()->back()->with('success', 'Le statut de la personne a bien été changé.');
    }

    public function edit(User $utilisateur): Response
    {
        return Inertia::render(
            'Admin/Users/Edit',
            [
                'subscriber' => $utilisateur->load('subscription.lesson', 'yearDatas'),
            ]
        );
    }

    public function update(UserUpdateRequest $request, User $utilisateur): RedirectResponse
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

        if ($request->has('suggestions')) {
            $utilisateur->suggestions()->sync($request->get('suggestions'));
        }

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $utilisateur)
    {
        $utilisateur->delete();
    }

    public function updateLesson(Request $request)
    {
    }

    public function archiveUser(User $user)
    {
        $user->roles()->detach();
        $user->assignRole('archived');
    }

    public function preSubscribed(): Response
    {
        $users = User::role('subscriber')
            ->has('subscription')
            ->with('subscription', function ($query) {
                $query->whereIn('status', [
                    Subscription::PENDING,
                    Subscription::NEEDS_INFOS,
                    Subscription::AWAITING_PAYMENT,
                ]);
            })
            ->where('lesson_id', null)
            ->get();

        return Inertia::render('Admin/Users/Presubscribed', compact('users'));
    }

    public function subscribing(User $user): Response
    {
        return Inertia::render(
            'Admin/Users/Subscribing',
            [
                'subscriber' => $user->load('subscription.lesson', 'currentYearData.file'),
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

    public function resetPassword(Request $request)
    {
        DB::table('users')
            ->where('id', $request->get('id'))
            ->update(['password' => Hash::make('password')]);
    }

    public function renewal(User $user): Response
    {
        $subscription = Subscription::where([
            ['status', Subscription::SUBSCRIPTION_OVER],
            ['user_id', $user->id],
        ])->first();

        $lessons = Lesson::query()
            ->where('year', now()->year . ' - ' . now()->addYear()->year)
            ->orderBy('title')
            ->get(['id', 'title'])
            ->map(fn ($lesson) => ['label' => $lesson->title, 'value' => $lesson->id]);

        $user->load('currentYearData', 'subscription');
        return Inertia::render('Admin/Users/Renewal', [
            'currentUser' => $user,
            'lessons' => $lessons,
            'subscription' => $subscription,
        ]);
    }

    public function storeRenewal(Request $request)
    {
        dd($request);
    }
}
