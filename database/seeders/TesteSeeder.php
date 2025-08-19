<?php

namespace Database\Seeders;

use App\Models\Escola;
use App\Models\Pagamento;
use App\Models\Repasse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TesteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'silvio@admin.com'],
            [
                'name' => 'Silvio Admin',
                'password' => Hash::make('123'),
                'role' => 'admin'
            ]
        );

        Escola::factory()->count(10)->create()->each(function ($escola) {

            User::factory()->create([
                'name' => 'Gestor ' . $escola->nome,
                'email' => 'gestor' . $escola->id . '@semed.com',
                'password' => Hash::make('123'),
                'role' => 'gestor',
                'escola_id' => $escola->id,
            ]);

            $repasse = Repasse::factory()->create([
                'escola_id' => $escola->id,
            ]);

            Pagamento::factory()->count(15)->create([
                'repasse_id' => $repasse->id,
                'escola_id' => $escola->id,
            ]);
        });
    }
}
