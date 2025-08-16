<?php

namespace Database\Factories;

use App\Enums\Titles;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $maleOrFemale = fake()->randomElement(['Male', 'Female']);
        $forename = fake()->firstName($maleOrFemale);
        $surname = fake()->lastName($maleOrFemale);
        $email = strtolower($forename) . '.' . strtolower($surname) . '@tasks-app.co.uk';

        return [
            'title' => $maleOrFemale === 'Male' ? Titles::MR : Titles::MRS,
            'forename' => $forename,
            'surname' => $surname,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'position' => fake()->jobTitle(),
            'gender' => $maleOrFemale,
            'telephone' => fake()->phoneNumber(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return UserFactory
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
