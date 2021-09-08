<?php

namespace Database\Seeders;

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
        $user = User::factory()->create();
        foreach(['administrator', 'subscriber'] as $role) {
            Role::create(['name' => $role]);
        }

        $user->assignRole('administrator');
    }
}
