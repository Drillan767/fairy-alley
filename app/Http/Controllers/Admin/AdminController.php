<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminController
{
    public function index(): Response
    {
        return Inertia::render('Admin/Admin');
    }

    public function lessonList(): JsonResponse
    {
        $lessons = Lesson::all();
        $events = [];

        foreach ($lessons as $lesson) {
            foreach($lesson->schedule as $schedule) {
                if (isset($schedule['status']) && $schedule['status'] !== 'cancelled') {
                    $time = Carbon::parse($schedule['date']);
                    $events[] = [
                        'title' => $lesson->title,
                        'start' => $time->format('Y-m-d H:i:s'),
                        'end' => $time->addHour()->addMinutes(30)->format('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        return response()->json($events);
    }
}
