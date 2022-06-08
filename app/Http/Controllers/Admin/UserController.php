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
use App\Notifications\RenewalStatusChanged;
use App\Services\FileHandler;
use App\Services\FirstContactHandler;
use App\Services\SubscriptionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Valuestore\Valuestore;

class UserController extends Controller
{
    public function __construct(protected SubscriptionHandler $subscriptionHandler)
    {
    }

    public function index(): Response
    {
        $roles = config('roles');
        $lessons = Lesson::orderBy('title')->get(['id', 'title', 'year']);
        $users = User::with('subscription.lesson')->get([
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

    public function renewalIndex(): Response
    {
        $renewals = Valuestore::make(storage_path('app/renewal.json'))->all();
        $lessons = Lesson::where('year', now()->year . ' - ' . now()->addYear()->year)->get(['id', 'title']);
        $users = User::with('currentYearData')->get([
            'id',
            'firstname',
            'lastname',
            'email',
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
                    'paid' => $relatedInfos['paid'],
                    'documents' => $relatedInfos['documents']
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

    public function updateDecision (Request $request)
    {
        $renewals = Valuestore::make(storage_path('app/renewal.json'));
        $relatedRenewal = $renewals->get("user_{$request->get('user_id')}");
        $relatedRenewal['admin_decision'] = $request->get('lesson');
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
            ->orderBy('title')
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
        $renewalData = Valuestore::make(storage_path('app/renewal.json'));

        $user = User::find($request->get('user_id'));
        $user->load('currentYearData.file');
        $user->resubscription_status = $request->get('renewal_status');
         $user->save();

        $yearData = $user->currentYearData;

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
        $userRenewalInfos['payment'] = $request->get('payment_complete');

        $renewalData->put("user_$user->id", $userRenewalInfos);

        $user->notify(new RenewalStatusChanged());

        return redirect()->route('utilisateurs.index')->with('success', "Réinscription de l'utilisateur mise à jour avec succès.");
    }
}
