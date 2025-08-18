<?php

namespace Database\Seeders;

use App\Enums\Titles;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create super admin user
        $user = User::create([
            'title' => Titles::MR,
            'forename' => 'Phil',
            'surname' => 'Franklin',
            'email' => 'phil.franklin@tasks-app.co.uk',
            'telephone' => '01912345678',
            'gender' => 'Male',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'position' => 'Director',
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole(['super-admin']);
        // Create admin user
        $user = User::create([
            'title' => Titles::MR,
            'forename' => 'John',
            'surname' => 'Smith',
            'email' => 'john.smith@tasks-app.co.uk',
            'telephone' => '01912345677',
            'gender' => 'Male',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'position' => 'Director',
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole(['admin']);
        Task::factory(15)->create([
            'user_id' => $user->id
        ]);
        // Create standard user
        $user = User::create([
            'title' => Titles::MRS,
            'forename' => 'Jill',
            'surname' => 'Harbottle',
            'email' => 'jill.harbottle@tasks-app.co.uk',
            'telephone' => '01912345676',
            'gender' => 'Female',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'position' => 'Director',
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole(['user']);
        Task::factory(15)->create([
            'user_id' => $user->id
        ]);

        // Create admin users
        User::factory(19)->create()->each(function ($user) {
            $user->assignRole(['admin']);
            Task::factory(15)->create([
                'user_id' => $user->id
            ]);
        });

        // Create standard users
        User::factory(19)->create()->each(function ($user) {
            $user->assignRole(['user']);
            Task::factory(15)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
