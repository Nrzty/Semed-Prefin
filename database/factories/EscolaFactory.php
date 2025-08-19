<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Escola>
 */
class EscolaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome_escola' => 'EMEF ' . fake()->company(),
            'cnpj' => fake()->numerify('###.###.###-##'),
            'nome_diretor' => fake()->name(),
            'nome_presidente_conselho' => fake()->name(),
            'logradouro' => fake()->streetName(),
            'numero' => fake()->numberBetween(1, 100),
            'complemento' => fake()->streetName(),
            'bairro' => fake()->streetName(),
            'cidade' => fake()->city(),
            'uf' => fake()->randomElement(['SP', 'SE', 'RJ']),
            'cep' => '49740',
        ];
    }
}
