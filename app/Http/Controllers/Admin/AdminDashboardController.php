<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Escola;
use App\Models\Repasse;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{

    public function index()
    {
        $anoAtual = date('Y');

        $totalEscolas = Escola::count();

        $totalCusteio = Repasse::where('ano_exercicio', $anoAtual)->sum('valor_custeio');
        $totalCapital = Repasse::where('ano_exercicio', $anoAtual)->sum('valor_capital');

        $repassesFinalizados = Repasse::where('ano_exercicio', $anoAtual)->where('status', 'Finalizado')->get();
        $escolasComPrestacaoCompleta = $repassesFinalizados->groupBy('escola_id')
            ->filter(function ($repassesDaEscolas) {
                return $repassesDaEscolas->count() >= 4;
            })->count();

        $dadosGraficoDeBarras = Repasse::where('ano_exercicio', $anoAtual)
            ->select('numero_parcela', 'status', DB::raw('count(*) as total'))
            ->groupBy('numero_parcela', 'status')
            ->orderBy('numero_parcela')
            ->get();

        $progressoParcelas = [
            'aberto' => [0, 0, 0, 0],
            'finalizado' => [0, 0, 0, 0],
        ];

        foreach ($dadosGraficoDeBarras as $dados) {
            $parcelaIndex = $dados->numero_parcela - 1;

            if ($dados->status == 'Aberto') {
                $progressoParcelas['aberto'][$parcelaIndex] = $dados->total;
            } elseif ($dados->status == 'Finalizado') {
                $progressoParcelas['finalizado'][$parcelaIndex] = $dados->total;
            }
        }

        return view('admin.dashboard', compact(
            'totalEscolas',
            'totalCusteio',
            'totalCapital',
            'escolasComPrestacaoCompleta',
            'anoAtual',
            'progressoParcelas'));
    }
}
