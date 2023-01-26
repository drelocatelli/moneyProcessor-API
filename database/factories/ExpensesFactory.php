<?php

namespace Database\Factories;

use App\Models\Expenses\Expenses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpensesFactory extends Factory
{
    protected $model = Expenses::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->title,
            'total' => $this->faker->numberBetween(1,10)
        ];
    }
}
