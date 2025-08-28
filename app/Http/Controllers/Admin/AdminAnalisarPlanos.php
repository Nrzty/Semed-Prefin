<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanoAplicacao;

class AdminAnalisarPlanos extends Controller
{
    public function index()
    {
        $planos = PlanoAplicacao::with(['escola', 'user'])
            ->where('status', 'Em Análise')
            ->latest()
            ->paginate(15);

        return view('admin.analisar-planos.index', data: compact('planos'));
    }

    public function show(PlanoAplicacao $plano)
    {
        $plano->load(['escola', 'user', 'itens']);

        $totalCusteio = $plano->itens->where('categoria_despesa', 'Custeio')->sum('valor_total');
        $totalCapital = $plano->itens->where('categoria_despesa', 'Capital')->sum('valor_total');
        $totalGeral = $plano->itens->sum('valor_total');

        return view('admin.analisar-planos.show', data: compact(
            'plano',
            'totalCusteio',
            'totalCapital',
            'totalGeral'
        ));
    }
}
