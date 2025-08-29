<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanoAplicacao;
use Illuminate\Http\Request;

class AdminAnalisarPlanosController extends Controller
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

    public function aprovar(PlanoAplicacao $plano)
    {
        $plano->status = 'Aprovado';
        $plano->motivo_reprovacao = null;
        $plano->save();

        return redirect()->route('admin.planos.index')->with('success', 'Plano aprovado com sucesso!');
    }

    public function reprovar(Request $request, PlanoAplicacao $plano)
    {
        $request->validate([
            'motivo_reprovacao' => 'required|string|min:10',
        ]);

        $plano->status = 'Reprovado';
        $plano->motivo_reprovacao = $request->motivo_reprovacao;
        $plano->save();

        return redirect()->route('admin.planos.index')->with('success', 'Plano reprovado com sucesso!');
    }
}
