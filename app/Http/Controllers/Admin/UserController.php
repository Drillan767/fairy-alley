<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserRoleChanged;
use App\Http\Controllers\Controller;
use App\Jobs\SendRenewalEmails;
use App\Http\Requests\{FirstContactRequest, SubscriptionValidationRequest, UserUpdateRequest};
use App\Jobs\SendLessonChanged;
use App\Models\{Lesson, Service, Subscription, User, YearData};
use App\Notifications\RenewalStatusChanged;
use App\Services\{FileHandler, FirstContactHandler, SubscriptionHandler};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{DB, Hash};
use Inertia\{Inertia, Response};
use Spatie\Valuestore\Valuestore;

class UserController extends Controller
{
    public function __construct(protected SubscriptionHandler $subscriptionHandler)
    {
    }

    public function index(): Response
    {
        $roles = config('roles');
        $lessons = Lesson::query()
            ->where('type', 'lesson')
            ->get(['id', 'title', 'year']);
        $users = User::with('subscription.lesson')
            ->get([
            'id',
            'firstname',
            'lastname',
            'email',
            'phone',
            'pro',
            'resubscription_status',
            'lesson_id'
        ]);

        return Inertia::render('Admin/Users/List', compact('users', 'lessons', 'roles'));
    }

    public function create(): Response
    {
        $lessons = Lesson::all(['id', 'title']);

        return Inertia::render('Admin/Users/Create', compact('lessons'));
    }

    public function store(FirstContactRequest $request, FirstContactHandler $firstContactHandler)
    {
        $firstContactHandler->store($request);
        return redirect()->back()->with('success', "L'utilisateur a été créé et un mail de création de mot de passe lui a été envoyé");
    }

    public function show(User $utilisateur): Response
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $years = $settings->get('which_year') === 'current'
            ? now()->subYear()->year . ' - ' . now()->year
            : now()->year . ' - ' . now()->addYear()->year;

        $utilisateur->load('currentYearData.file', 'lesson', 'subscription', 'firstContactData');
        $lessons = Lesson::query()
            ->where('year', $years)
            ->whereJsonContains('gender', $utilisateur->gender)
            ->get(['id', 'title'])
            ->mapWithKeys(fn ($l) => [$l->id => $l->title]);

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

        if ($validated['role'] === 'subscriber') {
            $user->password = Hash::make('password');
            $user->save();
        }

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

        if ($request->has('payments')) {
            $utilisateur->currentYearData->update([
                'payments' => $request->get('payments'),
                'total' => $request->get('total'),
            ]);
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

    public function renewalIndex(): Response
    {
        $renewals = Valuestore::make(storage_path('app/renewal.json'))->all();
        $lessons = Lesson::query()
            ->where('year', now()->year . ' - ' . now()->addYear()->year)
            ->get(['id', 'title']);
        $users = User::with('currentYearData')
            ->whereRelation('roles', 'name', 'subscriber')
            ->whereNull('resubscribed_at')
            ->get([
                'id',
                'firstname',
                'lastname',
                'email',
                'resubscribed_at',
                'resubscription_status',
            ])
        ->map(function ($user) use ($renewals) {

            $user = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'resubscription_status' => $user->resubscription_status,
            ];

            if (array_key_exists("user_{$user['id']}", $renewals)) {
                $relatedInfos = $renewals["user_{$user['id']}"];

                $addedData = [
                    'choice1' => $relatedInfos['lesson_choices'][0],
                    'choice2' => $relatedInfos['lesson_choices'][1],
                    'decision' => $relatedInfos['admin_decision'],
                    'paid' => $relatedInfos['paid'] ?? false,
                    'documents' => $relatedInfos['documents'] ?? false
                ];

            } else {
                $addedData = [];
                foreach (['choice1', 'choice2', 'decision', 'paid', 'documents'] as $field) {
                    $addedData[$field] = null;
                }
            }

            return array_merge($user, $addedData);
        });

        return Inertia::render('Admin/Users/RenewalList', compact('renewals', 'lessons', 'users'));
    }

    public function updateDecision (Request $request): RedirectResponse
    {
        $renewals = Valuestore::make(storage_path('app/renewal.json'));
        $relatedRenewal = $renewals->get("user_{$request->get('user_id')}");
        $relatedRenewal['admin_decision'] = $request->get('lesson') === 'null' ? null : $request->get('lesson');
        $renewals->put("user_{$request->get('user_id')}", $relatedRenewal);

        return redirect()->route('utilisateur.renewal.index');
    }

    public function renewal(User $user): Response
    {
        $vs = Valuestore::make(storage_path('app/renewal.json'));
        $renewalData = $vs->all();

        $subscription = Subscription::where([
            ['status', Subscription::SUBSCRIPTION_OVER],
            ['user_id', $user->id],
        ])->first();

        $lessons = Lesson::query()
            ->where('year', now()->year . ' - ' . now()->addYear()->year)
            ->get(['id', 'title'])
            ->map(fn ($lesson) => ['label' => $lesson->title, 'value' => $lesson->id]);

        $user->load('currentYearData.file', 'subscription');

        return Inertia::render('Admin/Users/Renewal', [
            'currentUser' => $user,
            'lessons' => $lessons,
            'renewalData' => $renewalData["user_$user->id"] ?? [],
            'subscription' => $subscription,
        ]);
    }

    public function storeRenewal(Request $request, FileHandler $fileHandler): RedirectResponse
    {
        $statusChanged = false;
        $renewalData = Valuestore::make(storage_path('app/renewal.json'));

        $user = User::find($request->get('user_id'));
        $user->load('currentYearData.file');

        if ($user->resubscription_status !== $request->get('renewal_status')) {
            $statusChanged = true;
        }

        $user->resubscription_status = $request->get('renewal_status');
        $user->save();

        $yearData = $user->currentYearData;

        $yearData->health_data = $request->get('year_data')['health_data'];
        $yearData->observations = $request->get('year_data')['observations'];
        $yearData->total = $request->get('year_data')['total'];
        $yearData->payments = $request->get('year_data')['payments'];
        $yearData->save();

        if ($request->hasFile('year_data')) {
            $fileHandler->uploadOrReplace($request->file('year_data')['file'], $yearData, $user);
        }

        $userRenewalInfos = $renewalData->get("user_$user->id");
        $userRenewalInfos['admin_decision'] = $request->get('lesson_decision');
        $userRenewalInfos['documents'] = $request->get('documents_complete');
        $userRenewalInfos['paid'] = $request->get('payment_complete');

        $renewalData->put("user_$user->id", $userRenewalInfos);

        if ($statusChanged && $request->get('renewal_status') === 4) {
            $user->notify(new RenewalStatusChanged());
        }

        return redirect()->route('utilisateurs.index')->with('success', "Réinscription de l'utilisateur mise à jour avec succès.");
    }

    public function renewUsers (): RedirectResponse
    {
        $renewals = Valuestore::make(storage_path('app/renewal.json'));

        $users = User::query()
            ->with('subscription', 'currentYearData')
            ->where('resubscription_status', 2)
            ->get(['id', 'firstname', 'lastname', 'email']);

        $decisions = [];

        foreach ($users as $user) {
            $relatedRenewal = $renewals->get("user_$user->id");
            $user->lesson_id = $relatedRenewal['admin_decision'];
            $user->resubscription_status = null;
            $user->resubscribed_at = now();
            $user->save();

            $user->subscription->lesson_id = $relatedRenewal['admin_decision'];
            $user->subscription->save();

            $renewals->forget("user_$user->id");

            $decisions[$user->id] = intval($relatedRenewal['admin_decision']);
        }

        SendRenewalEmails::dispatchAfterResponse($users, $decisions);

        return redirect()->back()->with('success', 'Les utilisateurs ont bien été réinscrits pour l\'année prochaine.');
    }
}
