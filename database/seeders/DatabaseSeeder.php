<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->generateUsers();

    }

    private function generateUsers()
    {
        foreach(['administrator', 'subscriber'] as $role) {
            Role::create(['name' => $role]);
        }
        $users = User::factory(2)->create();


        $users[0]->assignRole('administrator');
        $users[1]->assignRole('subscriber');

        Lesson::factory()->create();
    }
}
