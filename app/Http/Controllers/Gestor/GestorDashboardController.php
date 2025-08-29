<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GestorDashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $escola = $user->escola()->with(
            [
                'repasses' => function ($query) {
                    $query->where('status', 'Aberto')->with('pagamentos');
                },
            ])->first();

        if (! $escola) {
            abort(403, 'Usuário não vinculado a uma escola.');
        }

        $repasseAtivo = $escola->repasses->sortByDesc('ano_exercicio')
            ->sortByDesc('numero_parcela')
            ->first();

        $saldoCusteio = 0;
        $saldoCapital = 0;

        $ultimosPagamentos = collect();

        if ($repasseAtivo) {

            $totalGastoCusteio = $repasseAtivo->totalGastoCusteio();
            $totalGastoCapital = $repasseAtivo->totalGastoCapital();

            $saldoCusteio = $repasseAtivo->valor_custeio - $totalGastoCusteio;
            $saldoCapital = $repasseAtivo->valor_capital - $totalGastoCapital;

            $ultimosPagamentos = $repasseAtivo->pagamentos->sortByDesc('data_pagamento_efetivo')->take(5);
        }

        return view('gestor.dashboard', compact([
            'escola',
            'repasseAtivo',
            'saldoCusteio',
            'saldoCapital',
            'ultimosPagamentos',
        ]));
    }
}
