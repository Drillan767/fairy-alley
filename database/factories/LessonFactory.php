<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dates = [];
        $date = now();

        for ($i = 0; $i < 5; $i++) {
            $dates[] = $date->toJSON();
            $date->addDays(2);
        }

        return [
            'title' => 'Gymnastique rééducative posturale globale',
            'year' => '2021 - 2022',
            'ref' => 'Gym',
            'description' => "Tousled 90's truffaut copper mug. Chartreuse tote bag activated charcoal tbh man bun coloring book godard.",
            'schedule' => $dates,
        ];
    }
}
