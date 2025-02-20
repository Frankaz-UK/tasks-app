<?php

namespace Database\Seeders;

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
            'forename' => 'Mr',
            'surname' => 'Frankaz',
            'email' => 'frankaz@tasks-app.co.uk',
            'telephone' => '01912345678',
            'gender' => 'male',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'position' => 'Director',
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole(['super-admin']);

        // Create admin users
        User::factory(20)->create()->each(function ($user) {
            $user->assignRole(['admin']);
        });

        // Create standard users
        User::factory(20)->create()->each(function ($user) {
            $user->assignRole(['user']);
        });
    }
}
