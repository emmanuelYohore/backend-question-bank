<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankItem>
 */
class BankItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => '019dd35f-460c-72b3-a786-cd35c37213d4',
            'name' => fake()->sentence(3),
            'archived' => false,
        ];
    }
}
