<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'complete' => (bool)random_int(0, 1),
            'description' => fake()->text(),
        ];
    }
}
