<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repasse>
 */
class RepasseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero_parcela' => fake()->numberBetween(1, 4),
            'ano_exercicio'=> fake()->year('now'),
            'status' => fake()->randomElement(['Aberto','Finalizado']),
            'valor_custeio' => fake()->randomFloat(2, 10000, 20000),
            'valor_capital' => fake()->randomFloat(2, 10000, 20000),
            'data_repasse' => fake()->dateTime('now'),
        ];
    }
}
