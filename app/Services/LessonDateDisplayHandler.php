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

            foreach ($statuses as $status) {
                $title = $lesson->title . match ($status) {
                    'ok' => '',
                    'cancelled' => ' - Annulé',
                    'recovery' => ' - Rattrapage'
                };

                // Set colors to distinguish
                if ($lesson->id === $userLesson) {
                } else {
                }
            }
        }

        return [];
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
