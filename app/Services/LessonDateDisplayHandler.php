<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Movement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class LessonDateDisplayHandler
{
    public function calculate(Authenticatable $user, $movements): array
    {
        $attributes = [];
        $userLesson = $user->lesson_id;
        $lessons = Lesson::with('movements')
            ->whereJsonContains('gender', $user->gender)
            ->orderBy('title')
            ->get();

        foreach ($lessons as $lesson) {
            $schedule = $this->handleMovements($lesson->schedule, $movements);
            $statuses = $schedule->groupBy('status');

            $color = match ($lesson->type) {
                'lesson' => 'blue',
                'conference' => 'orange',
                'workshop' => 'green',
                'private lesson' => 'pink',
            };

            foreach ($statuses as $status => $date) {
                $title = $lesson->title . match ($status) {
                    'cancelled' => ' - Annulé',
                    'recovery' => ' - Rattrapage',
                    'join' => ' - Rejoint',
                    'leave' => ' - Annulé par vous',
                    default => '',
                };

                if ($lesson->id === $userLesson) {
                    $highlight = [
                        'color' => $color,
                        'fillMode' => match ($status) {
                            'cancelled', 'leave' => 'outline',
                            'ok', 'recovery', 'join' => 'solid'
                        },
                    ];
                } else {
                    $highlight = [
                        'color' => $color,
                        'fillMode' => 'light',
                    ];
                }

                if ($status === 'join') {
                    $order = 6;
                } elseif ($lesson->id === $userLesson) {
                    $order = 5;
                } else {
                    $order = 0;
                }

                $attributes[] = [
                    'popover' => [
                        'label' => $title
                    ],
                    'highlight' => $highlight,
                    'customData' => [
                        'color' => $color,
                        'cancelled' => $status === 'cancelled',
                        'isSubscribed' => $lesson->id === $userLesson,
                        'lesson_id' => $lesson->id,
                        'lesson_title' => $title,
                        'movements' => $lesson->movements,
                    ],
                    'dates' => $date
                        ->filter(fn($s) => Carbon::parse($s['date']) > now())
                        ->map(fn($s) => $s['date'])
                        ->values(),
                    'order' => $order,
                ];
            }
        }

        return $attributes;
    }

    private function handleMovements($dates, $movements): Collection
    {
        foreach($movements as $movement) {
            $lessonTime = Carbon::parse($movement->lesson_time)->format('Y-m-d H:i');

            foreach ($dates as $i => $date) {
                if ($date['date'] === $lessonTime) {
                    $dates[$i]['status'] = $movement->action;
                }
            }
        }

        return collect($dates);
    }
}
