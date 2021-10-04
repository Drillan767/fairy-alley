<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Lesson;
use App\Services\SubscriptionHandler;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    /*
     * Envoyer une notif à l'admin
     */

    public function __construct(public SubscriptionHandler $subscriptionHandler)
    {}

    public function index(): Response
    {
        $user = request()->user();
        if ($user->lesson === null && !$user->subscription()->exists()) {
            $subscribed = false;
            $data = Lesson::all();
            $headlines = collect(config('lesson.headlines'))->firstWhere('status_id', 0);
        } else {
            $subscribed = true;
            $data = null;
            $user->load('subscription.lesson');
            $headlines = collect(config('lesson.headlines'))->firstWhere('status_id', $user->subscription->status);
        }

        return Inertia::render('User/Landing', compact('subscribed', 'data', 'headlines'));
    }

    public function create(Lesson $lesson)
    {
        if (auth()->user()->subscription !== null || auth()->user()->lesson_id !== null) {
            return redirect()->route('profile.index')->with('error', 'Votre inscription a déjà été enregistrée.');
        }

        $lessons = Lesson::all('title');
        $details = config('lesson.tos');
        return Inertia::render('User/Subscription/Create',
            compact('lesson', 'lessons', 'details')
        );
    }

    public function edit()
    {

    }

    public function store(SubscriptionRequest $request)
    {
        $this->subscriptionHandler->create($request);
        return redirect()->route('profile.index')->with('success', 'Votre inscription a bien été prise en compte');
    }
}
