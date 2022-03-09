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
        $lessons = Lesson::all();

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
                        'ok' => 'blue',
                        'recovery' => 'green',
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
                        'isSubscribed' => $lesson->id === $userLesson,
                        'lesson_id' => $lesson->id,
                        'lesson_title' => $lesson->title,
                    ],
                    'dates' => $date->map(fn($s) => $s['date']),
                    'order' => $lesson->id === $userLesson ? 5 : 0,
                ];
            }
        }

        return $attributes;
    }

    /*
             foreach($statuses as $status => $date) {
                $label = "Cours du $lesson";
                $color = '';

                switch($status) {
                    case 'ok':
                        $color = 'purple';
                        break;
                    case 'cancelled':
                       $color = 'red';
                       $label .= ' - Annulé';
                       break;
                    case 'recovery':
                        $color = 'blue';
                        $label .= ' - Rattrapage';
                        break;
                }

                $attributes[] = [
                    'popover' => [
                        'label' => $label
                    ],
                    'highlight' => [
                        'color' => $color,
                        'fillMode' => 'solid',
                    ],
                    'dates' => $date->map(fn($s) => $s['date']),
                ];

            }
     */
}
