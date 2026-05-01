<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'uuid_expenses' => Str::uuid(),
            'amount' => $this->faker->numberBetween(1000, 100000),
            'description' => $this->faker->sentence(),
            'status' => 'pending',
            'date' => now(),
        ];
    }
}
