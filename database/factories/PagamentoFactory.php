<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pagamento>
 */
class PagamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome_fornecedor' => fake()->name(),
            'cnpj_cpf_fornecedor' => fake()->numerify('###.###.###-##'),
            'tipo_despesa' => fake()->randomElement(['Material de Custeio', 'Prestação de Serviço', 'Material de Capital']),
            'numero_nota_fiscal' => fake()->numberBetween(1, 100),
            'data_emissao_documento' => fake()->dateTime('now'),
            'data_pagamento_efetivo' => fake()->dateTime('now'),
            'numero_cheque' => fake()->numberBetween(1, 100),
            'data_vencimento_cheque' => fake()->dateTime('now'),
            'valor_total_pagamento' => fake()->randomFloat(2, 10000, 20000),
        ];
    }
}
