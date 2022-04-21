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
            ->where('type', 'lesson')
            ->orderBy('title')
            ->get();

        foreach ($lessons as $lesson) {
            $schedule = $this->handleMovements($lesson->schedule, $movements);
            $statuses = $schedule->groupBy('status');

            foreach ($statuses as $status => $date) {
                $title = $lesson->title . match ($status) {
                    'cancelled' => ' - Annulé',
                    'recovery' => ' - Rattrapage',
                    'join' => ' - Rejoint',
                    'leave' => ' - Annulé par vous',
                    default => '',
                };

                // Set colors to distinguish
                if ($lesson->id === $userLesson) {

                    $color = match ($status) {
                        'cancelled', 'leave' => 'red',
                        'recovery', 'ok' => 'blue',
                        default => '',
                    };

                } else {
                    $color = match ($status) {
                        'cancelled' => 'red',
                        'join' => 'green',
                        default => 'gray',
                    };
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
                    'highlight' => [
                        'color' => $color,
                        'fillMode' => $status === 'leave' ? 'outline' : 'solid',
                    ],
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
