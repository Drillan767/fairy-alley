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
        $roleList = [
            'administrator',
            'first_contact',
            'guest',
            'presubscribed',
            'subscriber',
            'substitute',
            'archived',
        ];

        foreach($roleList as $role) {
            Role::findOrCreate($role);
        }

        $users = User::factory(2)->create();

        $users[0]->assignRole('administrator');
        $users[1]->assignRole('guest');

        Lesson::factory()->create();
    }
}
