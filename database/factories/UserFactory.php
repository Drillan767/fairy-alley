<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $response = Http::get('https://randomuser.me/api', [
            'nat' => 'fr',
            'inc' => 'name,phone,cell,email,location,dob,',
        ])->body();
        $user = json_decode($response)->results[0];
        $gender = $user->name->title === 'Mr' ? 'M' : 'F';

        return [
            'gender' => $gender,
            'firstname' => $user->name->first,
            'lastname' => $user->name->last,
            'address1' => $user->location->street->number . ', '. $user->location->street->name,
            'zipcode' => $user->location->postcode,
            'city' => $user->location->city,
            'email' => $user->email,
            'birthday' => Carbon::parse($user->dob->date),
            'phone' => str_replace('-', '', $user->phone),
            'pro' => str_replace('-', '', $user->cell),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
