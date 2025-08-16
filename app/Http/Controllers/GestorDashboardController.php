<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GestorDashboardController extends Controller
{

    public function index(){

        $user = Auth::user();

        $escola = $user->escola()->with(
            [
                'repasses' => function ($query) {
                    $query->where('status', 'Aberto')->with('pagamentos');
                }
            ])->first();

        if (!$escola){
            abort(403, 'Usuário não vinculado a uma escola.');
        }

        $repasseAtivo = $escola->repasses->sortByDesc('ano_exercicio')
                                          ->sortByDesc('numero_parcela')
                                          ->first();

        $saldoCusteio = 0;
        $saldoCapital = 0;

        $ultimosPagamentos = collect();

        if ($repasseAtivo) {
            
            $totalGastoCapital = $repasseAtivo->pagamentos->where('tipo_despesa', 'Material Permanente')
                                                          ->sum('valor_total_pagamento');

            $totalGastoCusteio = $repasseAtivo->pagamentos->where('tipo_despesa', 'Material de Custeio')
                                                          ->sum('valor_total_pagamento');
        
            $totalGastoServicos = $repasseAtivo->pagamentos->where('tipo_despesa', 'Prestacao de Serviço')
                                                           ->sum('valor_total_pagamento');

            $saldoCusteio = $repasseAtivo->valor_custeio - ($totalGastoCusteio + $totalGastoServicos);

            $saldoDeCapital = $repasseAtivo->valor_capital -$totalGastoCapital;

            $ultimosPagamentos = $repasseAtivo->pagamentos->sortByDesc('data_pagamento')->take(5);
        }

        return view('gestor.dashboard', compact([
            'escola',
            'repasseAtivo',
            'saldoCusteio',
            'saldoCapital',
            'ultimosPagamentos'
        ]));
    }
}
