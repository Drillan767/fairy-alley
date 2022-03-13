<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Lesson;
use App\Services\LessonDateDisplayHandler;
use App\Services\SubscriptionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function __construct(public SubscriptionHandler $subscriptionHandler)
    {
    }

    public function index(LessonDateDisplayHandler $displayHandler): Response
    {
        $attributes = [];
        $user = auth()->user();
        $headlines = collect(config('lesson.headlines'))->firstWhere('status_id', $user->subscription->status);

        // suggestions
        if ($user->hasAnyRole('subscriber', 'guest', 'substitute')) {
            $user->load('suggestions.thumbnail', 'suggestions.page');

            $attributes = $displayHandler->calculate($user);
        }

        return Inertia::render('User/Landing', compact('headlines', 'attributes'));
    }

    public function create(Lesson $lesson): Response|RedirectResponse
    {
        if (auth()->user()->subscription !== null || auth()->user()->lesson_id !== null) {
            return redirect()->route('profile.index')->with('error', 'Votre inscription a déjà été enregistrée.');
        }

        $lessons = Lesson::all('title');
        $details = config('lesson.tos');

        return Inertia::render(
            'User/Subscription/Create',
            compact('lesson', 'lessons', 'details')
        );
    }

    public function edit(Lesson $lesson): Response|RedirectResponse
    {
        $user = auth()->user()->load('subscription', 'currentYearData.file');
        $lessons = Lesson::all('title');
        $details = config('lesson.tos');

        return Inertia::render(
            'User/Subscription/Edit',
            compact('lesson', 'lessons', 'details', 'user')
        );
    }

    public function store(SubscriptionRequest $request): RedirectResponse
    {
        $this->subscriptionHandler->create($request);

        return redirect()->route('profile.index')->with('success', 'Votre inscription a bien été prise en compte');
    }

    public function update(SubscriptionRequest $request): RedirectResponse
    {
        $this->subscriptionHandler->update($request);

        return redirect()->route('profile.index')->with('success', 'Votre inscription a bien été prise en compte');
    }

    public function lessonDetail(Request $request)
    {

    }
}
