<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use App\Models\Movement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController
{
    public function index(): Response
    {
        return Inertia::render('Admin/Index/Admin');
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
                        'extendedProps' => [
                            'lesson_id' => $lesson->id,
                            'hour' => $schedule['date'],
                        ]
                    ];
                }
            }
        }

        return response()->json($events);
    }

    public function details(Request $request): JsonResponse
    {
        $userList = User::query()
            ->select('id', 'firstname', 'lastname', 'phone', 'pro')
            ->where('lesson_id', $request->get('lesson_id'))
            ->get();

        $lesson = Lesson::select('id', 'title') ->find($request->get('lesson_id'));

        Movement::with('user')
            ->where('lesson_id', $lesson->id)
            ->where('lesson_time', $request->get('hour'))
            ->get()
            ->each(function ($movement) use (&$userList) {
                if ($movement->action === 'join') {
                    $userList->push($movement->user);
                } else {
                    $userList = $userList->reject(fn($user) => $user->id === $movement->user->id);
                }
            });

        return response()->json([
            'userList' => $userList,
            'lesson' => $lesson->title,
        ]);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $movement = new Movement();
        $movement->lesson_id = $request->get('lesson_id');
        $movement->user_id = $request->get('user_id');
        $movement->lesson_time = $request->get('lesson_time');
        $movement->action = 'leave';
        $movement->by_admin = true;
        $movement->save();

        return response()->json('ok');
    }
}
