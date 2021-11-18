<?php

namespace Database\Factories;

use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'Gymnastique rééducative posturale globale',
            'year' => '2021 - 2022',
            'ref' => 'Gym',
            'description' => "Tousled 90's truffaut copper mug. Chartreuse tote bag activated charcoal tbh man bun coloring book godard.",
            'schedule' => [
                [
                    'day' => 'Lundi',
                    'begin' => '09:30',
                    'end' => '10:45',
                ],
                [
                    'day' => 'Lundi',
                    'begin' => '17:30',
                    'end' => '18:45',
                ],
                [
                    'day' => 'Mardi',
                    'begin' => '08:30',
                    'end' => '9:45',
                ],
                [
                    'day' => 'Mardi',
                    'begin' => '10:15',
                    'end' => '11:30',
                ],
                [
                    'day' => 'Mardi',
                    'begin' => '17:00',
                    'end' => '18:15',
                ],
                [
                    'day' => 'Jeudi',
                    'begin' => '08:00',
                    'end' => '09:15',
                ],
                [
                    'day' => 'Jeudi',
                    'begin' => '09:45',
                    'end' => '11:00',
                ],
                [
                    'day' => 'Jeudi',
                    'begin' => '18:00',
                    'end' => '19:15',
                ],
                [
                    'day' => 'Vendredi',
                    'begin' => '09:15',
                    'end' => '10:30',
                ],
            ]
        ];
    }
}
