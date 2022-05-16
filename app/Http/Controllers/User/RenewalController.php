<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Subscription;
use App\Models\User;
use App\Models\YearData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RenewalController extends Controller
{
    public function index()
    {
        $lessons = Lesson::query()
            ->whereJsonContains('gender', auth()->user()->gender)
            ->where('type', 'lesson')
            ->where('year', now()->year . ' - ' . now()->addYear()->year)
            ->orderBy('title')
            ->get(['id', 'title'])
            ->map(fn ($lesson) => ['value' => $lesson->id, 'label' => $lesson->title])
        ;

        return Inertia::render('User/Renewal/Index', compact('lessons'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $yearData = new YearData();
        $yearData->user_id = $user->id;
        $yearData->reply_transmitted_via = 'website';
        $yearData->total = 0;
        $yearData->last_year_class = $user->lesson->title;

        foreach (['health_issues', 'current_health_issues', 'medical_treatment'] as $hFields) {
            if ($request->get($hFields) !== null && $request->get($hFields) !== '') {
                $yearData->health_data = $request->get($hFields) . "\n\n";
            }
        }

        $yearData->save();

        $subscription = new Subscription();
        $subscription->user_id = auth()->id();
        $subscription->lesson_id = $request->get('lesson_id');
        $subscription->status = Subscription::SUBSCRIPTION_OVER;
        $subscription->save();

        $user->resubscription_status = Subscription::PENDING;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Votre réinscription a bien été enregistrée.
        Vous pourrez suivre son avancement directement sur votre profil.');
    }
}
