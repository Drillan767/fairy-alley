<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Lesson;
use App\Services\SubscriptionHandler;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /*
     * Envoyer une notif à l'admin
     */

    public function __construct(public SubscriptionHandler $subscriptionHandler)
    {}

    public function index()
    {
        $user = request()->user();
        if ($user->lesson === null) {
            $subscribed = false;
            $data = Lesson::all();
        } else {
            $subscribed = true;
            $data = $user->lesson;
        }

        return Inertia::render('User/Landing', compact('subscribed', 'data'));
    }

    public function create(Lesson $lesson)
    {
        if (auth()->user()->subscription !== null || auth()->user()->lesson_id !== null) {
            return redirect()->route('profile.index')->with('error', 'Votre inscription a déjà été enregistrée.');
        } else {
            $lessons = Lesson::all()->map(fn ($l) => ['id' => $l->id, 'title' => $l->title]);
            return Inertia::render('User/Subscription/Create', compact('lesson', 'lessons'));
        }
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
