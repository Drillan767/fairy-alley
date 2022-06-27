<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RenewalRequest;
use App\Models\{Lesson, YearData};
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
        $decidedLessons = $this->countLessonDecisions($renewal->all());

        $tos = $config->get('tos');

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
            ->map(function ($lesson) use ($decidedLessons) {
                $lessonTitle = array_key_exists($lesson->id, $decidedLessons) && $decidedLessons[$lesson->id] >= 10 ? ' (Complet)' : '';
                return [
                    'value' => $lesson->id,
                    'label' => $lesson->title . $lessonTitle,
                    'gender' => $lesson->gender
                ];
            })
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

    private function countLessonDecisions(array $renewals): array
    {
        $result = [];
        foreach ($renewals as $renewal) {
            isset($result[$renewal['admin_decision']]) ? $result[$renewal['admin_decision']]++ : $result[$renewal['admin_decision']] = 1;
        }

        return $result;
    }
}
