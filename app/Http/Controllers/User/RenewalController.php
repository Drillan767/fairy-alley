<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Inertia\Inertia;

class RenewalController extends Controller
{
    public function index()
    {
        $lessons = Lesson::query()
            ->whereJsonContains('gender', auth()->user()->gender)
            ->where('type', 'lesson')
            ->where('year', now()->get('year') . ' - ' . now()->addYear()->get('year'))
            ->orderBy('title')
            ->get(['id', 'title'])
            ->map(fn ($lesson) => ['value' => $lesson->id, 'label' => $lesson->title])
        ;

        return Inertia::render('User/Renewal/Index', compact('lessons'));
    }

    public function store()
    {
        // Create both subscription and year data on submit
        // Subscription contains the data for the upcoming year

    }
}
