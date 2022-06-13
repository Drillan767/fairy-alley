<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RenewalRequest;
use App\Models\Lesson;
use App\Models\YearData;
use App\Services\SubscriptionHandler;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Spatie\Valuestore\Valuestore;

class RenewalController extends Controller
{
    public function index()
    {
        $config = Valuestore::make(storage_path('app/settings.json'));
        $renewal = Valuestore::make(storage_path('app/renewal.json'));

        $tos = [];

        foreach (['details', 'process', 'organization', 'conditions'] as $field) {
            $tos[$field] = $config->get($field);
        }

        $relatedRenewal = $renewal->get('user_' . auth()->id());

        if (!$relatedRenewal) {
            $relatedRenewal = [];
        }

        $yearData = auth()->user()->resubscription_status
            ? YearData::query()
                ->with('file')
                ->where('user_id', auth()->id())
                ->latest()
                ->first()
            : [];

        $lessons = Lesson::query()
            ->where('type', 'lesson')
            ->where('year', now()->year . ' - ' . now()->addYear()->year)
            ->orderBy('title')
            ->get(['id', 'title', 'gender'])
            ->map(fn ($lesson) => ['value' => $lesson->id, 'label' => $lesson->title, 'gender' => $lesson->gender])
        ;

        return Inertia::render('User/Renewal/Index', compact('tos', 'lessons', 'relatedRenewal', 'yearData'));
    }

    public function update(RenewalRequest $request, SubscriptionHandler $subscriptionHandler): RedirectResponse
    {

        $method = $request->user()->resubscription_status ? 'update' : 'create';

        $subscriptionHandler->$method($request);

        return redirect()->route('profile.index')->with('success', 'Votre réinscription a bien été enregistrée.
        Vous pourrez suivre son avancement directement sur votre profil.');
    }
}
