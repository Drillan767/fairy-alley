<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\User;

class LessonDateDisplayHandler
{
    public function calculate(User $user): array
    {
        $attributes = [];
        $userLesson = $user->lesson_id;
        $lessons = Lesson::with('movements', 'queues')->get();

        foreach ($lessons as $lesson) {
            $schedule = collect($lesson->schedule);
            $statuses = $schedule->groupBy('status');

            foreach ($statuses as $status => $date) {
                $title = $lesson->title . match ($status) {
                    'cancelled' => ' - Annulé',
                    'recovery' => ' - Rattrapage',
                    default => '',
                };

                // Set colors to distinguish
                if ($lesson->id === $userLesson) {
                    $color = match ($status) {
                        'cancelled' => 'red',
                        'recovery', 'ok' => 'blue',
                        default => '',
                    };

                } else {
                    $color = 'gray';
                }

                $attributes[] = [
                    'popover' => [
                        'label' => $title
                    ],
                    'highlight' => [
                        'color' => $color,
                        'fillMode' => 'solid',
                    ],
                    'customData' => [
                        'color' => $color,
                        'cancelled' => $status === 'cancelled',
                        'isSubscribed' => $lesson->id === $userLesson,
                        'lesson_id' => $lesson->id,
                        'lesson_title' => $lesson->title,
                        'queues' => $lesson->queues,
                        'movements' => $lesson->movements,
                    ],
                    'dates' => $date->map(fn($s) => $s['date']),
                    'order' => $lesson->id === $userLesson ? 5 : 0,
                ];
            }
        }

        return $attributes;
    }

    // Find a way to retrieve automatically every lesson LEFT for the week
}
