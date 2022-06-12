<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RenewalRequest;
use App\Models\Lesson;
use App\Services\SubscriptionHandler;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Spatie\Valuestore\Valuestore;

class RenewalController extends Controller
{
    public function index()
    {
        $config = Valuestore::make(storage_path('app/settings.json'));
        $tos = [];

        foreach (['details', 'process', 'organization', 'condition'] as $field) {
            $tos[$field] = $config->get($field);
        }

        $lessons = Lesson::query()
            ->where('type', 'lesson')
            ->where('year', now()->year . ' - ' . now()->addYear()->year)
            ->orderBy('title')
            ->get(['id', 'title', 'gender'])
            ->map(fn ($lesson) => ['value' => $lesson->id, 'label' => $lesson->title, 'gender' => $lesson->gender])
        ;

        return Inertia::render('User/Renewal/Index', compact('tos', 'lessons'));
    }

    public function update(RenewalRequest $request, SubscriptionHandler $subscriptionHandler): RedirectResponse
    {

        $method = $request->user()->resubscription_status ? 'update' : 'create';

        $subscriptionHandler->$method($request);

        return redirect()->route('profile.index')->with('success', 'Votre réinscription a bien été enregistrée.
        Vous pourrez suivre son avancement directement sur votre profil.');
    }
}
