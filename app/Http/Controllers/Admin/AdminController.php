<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use App\Models\Movement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard/Index');
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
                        'title' => $lesson->title . ' (' . $this->listUsers($lesson->id, $time->format('Y-m-d H:i:s')) . ' personnes)',
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
        $lesson = Lesson::select('id', 'title') ->find($request->get('lesson_id'));

        $userList = $this->listUsers($lesson->id, $request->get('hour'), true);

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

    private function listUsers(int $lesson_id, string $hour, $loadInfos = false): Collection|int
    {
        if ($loadInfos) {
            $userList = User::query()
                ->select('id', 'firstname', 'lastname', 'phone', 'pro')
                ->where('lesson_id', $lesson_id)
                ->get();
        } else {
            $userList = User::where('lesson_id', $lesson_id)->count();
        }

        Movement::with('user')
            ->where('lesson_id', $lesson_id)
            ->where('lesson_time', $hour)
            ->get()
            ->each(function ($movement) use (&$userList, $loadInfos) {
                if ($movement->action === 'join') {
                    if($loadInfos) {
                        $userList->push($movement->user);
                    } else {
                        $userList++;
                    }
                } else {
                    if ($loadInfos) {
                        $userList = $userList->reject(fn($user) => $user->id === $movement->user->id);
                    } else {
                        $userList--;
                    }
                }
            });

        return $userList;
    }
}
