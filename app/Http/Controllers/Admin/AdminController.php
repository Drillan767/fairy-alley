<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendRevivalMails;
use App\Models\{Lesson, Movement, User};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Inertia\{Inertia, Response};

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
                $time = Carbon::parse($schedule['date']);
                // Check if locked or is holiday, add color if so.
                $events[] = [
                    'title' => $lesson->title . '<br /> (' . $this->listUsers($lesson->id, $time->format('Y-m-d H:i:s')) . ' personnes)',
                    'start' => $time->format('Y-m-d H:i:s'),
                    'end' => $time->addHour()->addMinutes(30)->format('Y-m-d H:i:s'),
                    'color' => match ($schedule['status']) {
                        'cancelled' => 'red',
                        'locked' => 'orange',
                        default => '#3788d8',
                    },
                    'extendedProps' => [
                        'status' => $schedule['status'],
                        'lesson_id' => $lesson->id,
                        'hour' => $schedule['date'],
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    public function details(Request $request): JsonResponse
    {
        $lesson = Lesson::select('id', 'title')->find($request->get('lesson_id'));

        $userList = $this->listUsers($lesson->id, $request->get('hour'), true);

        return response()->json([
            'userList' => $userList,
            'lesson' => $lesson->title,
        ]);
    }

    public function getUsers(Request $request): JsonResponse
    {
        $lesson_id = $request->get('lesson_id');
        $hour = Carbon::parse($request->get('hour'));

        $users = User::query()
            ->with(['movements' => function ($query) use ($lesson_id, $hour) {
                // User hasn't already join THIS SPECIFIC lesson.
                $query->whereNot([
                    ['lesson_id', $lesson_id],
                    ['lesson_time', $hour],
                    ['action', 'join']
                ]);
            }])
            // User must be able to subscribe.
            ->whereNot('lesson_id', $lesson_id)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['subscriber', 'substitute', 'administrator']);
            })

            // User isn't already in this lesson
            ->get(['id', 'firstname', 'lastname'])
            ->map(function ($user) {
                return [
                    'value' => $user->id,
                    'name' => $user->full_name,
                ];
            });

        return response()->json($users);
    }

    public function subscribe(Request $request): RedirectResponse
    {
        foreach ($request->get('users') as $userId) {
            Movement::create([
                'user_id' => $userId,
                'lesson_id' => $request->get('lesson'),
                'lesson_time' => Carbon::parse($request->get('date')),
                'action' => 'join',
                'by_admin' => true,
            ]);
        }

        return redirect()
            ->route('admin.index')
            ->with('success', count($request->get('users')) . ' utilisateur(s) inscrit(s) avec succès.');
    }

    public function unsubscribe(Request $request): RedirectResponse
    {
        Movement::create([
            'user_id' => $request->get('user_id'),
            'lesson_id' => $request->get('lesson'),
            'lesson_time' => Carbon::parse($request->get('date')),
            'action' => 'leave',
            'by_admin' => true,
        ]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Membre désinscrit inscrit(s) avec succès.');
    }

    public function lock(Request $request)
    {
        $date = Carbon::parse($request->get('date'));
        $lock = $request->get('lock');

        $lesson = Lesson::find($request->get('lesson'));
        $schedule = $lesson->schedule;

        foreach ($schedule as $i => $s) {
            if ($date->format('Y-m-d H:i') === $s['date']) {
                $schedule[$i] = [
                    'date' => $s['date'],
                    'status' => $lock ? 'locked' : 'ok',
                ];
            }
        }

        $lesson->schedule = $schedule;
        $lesson->save();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Le cours a été verrouillé avec succès.');
    }

    private function listUsers(int $lesson_id, string $hour, $loadInfos = false): Collection|int
    {
        if ($loadInfos) {
            $userList = User::select('id', 'firstname', 'lastname', 'phone', 'pro')
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

    public function revive(Request $request): JsonResponse
    {
        SendRevivalMails::dispatchAfterResponse($request->get('type'));
        return response()->json('ok');
    }
}
