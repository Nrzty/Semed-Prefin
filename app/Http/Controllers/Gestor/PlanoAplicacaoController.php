<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\PlanoAplicacao;
use App\Models\PlanoAplicacaoItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PlanoAplicacaoController extends Controller
{
    public function index()
    {
        $planos = PlanoAplicacao::where('escola_id', Auth::user()->escola_id)
            ->with('itens')
            ->latest()
            ->paginate(15);

        return view('gestor.planos-aplicacao.index', compact('planos'));
    }

    public function create()
    {
        return view('gestor.planos-aplicacao.create');
    }

    public function edit($planoId)
    {
        $plano = PlanoAplicacao::findOrFail($planoId);

        if ($plano->escola_id != Auth::user()->escola_id) {
            abort(403, 'Acesso não autorizado!');
        }

        if ($plano->status !== 'Reprovado') {
            return redirect()->route('gestor.plano-aplicacao.index')->with('error', 'Apenas planos reprovados podem ser revisados.');
        }

        $plano->load('itens');

        return view('gestor.planos-aplicacao.edit', data: compact('plano'));
    }

    public function update(Request $request, PlanoAplicacao $plano)
    {
        if ($plano->escola_id !== Auth::user()->escola_id) {
            abort(403);
        }

        $validated = $request->validate([
            'itens_json' => 'required|json',
        ]);

        $itens = json_decode($validated['itens_json'], true);

        if (empty($itens)) {
            return back()->with('error', 'O plano não pode ficar sem itens.');
        }

        DB::beginTransaction();
        try {
            $plano->update([
                'status' => 'Em Análise',
                'motivo_reprovacao' => null,
            ]);

            $plano->itens()->delete();

            foreach (json_decode($request->itens_json, true) as $item) {
                PlanoAplicacaoItem::create([
                    'plano_aplicacao_id' => $plano->id,
                    'descricao' => $item['descricao'],
                    'categoria_despesa' => $item['categoria_despesa'],
                    'unidade' => $item['unidade'] ?? 'Un',
                    'quantidade' => $item['quantidade'],
                    'valor_unitario' => $item['valor_unitario'],
                    'valor_total' => $item['quantidade'] * $item['valor_unitario'],
                ]);
            }

            DB::commit();
            return redirect()->route('gestor.plano-aplicacao.index')->with('success', 'Plano atualizado e reenviado com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocorreu um erro ao reenviar o plano.');
        }
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'itens_json' => 'required|json',
        ]);

        $itens = json_decode($request->input('itens_json'), true);

        if (empty($itens)) {
            return back()->withErrors(['geral' => 'É necessário adicionar pelo menos um item ao plano.']);
        }

        DB::beginTransaction();
        try {
            $plano = PlanoAplicacao::create([
                'user_id' => Auth::id(),
                'escola_id' => Auth::user()->escola_id,
                'status' => 'Em Análise',
            ]);

            foreach (json_decode($request->itens_json, true) as $item) {
                PlanoAplicacaoItem::create([
                    'plano_aplicacao_id' => $plano->id,
                    'descricao' => $item['descricao'],
                    'categoria_despesa' => $item['categoria_despesa'],
                    'unidade' => $item['unidade'] ?? 'Un',
                    'quantidade' => $item['quantidade'],
                    'valor_unitario' => $item['valor_unitario'],
                    'valor_total' => $item['quantidade'] * $item['valor_unitario'],
                ]);
            }

            DB::commit();
            return redirect()->route('gestor.plano-aplicacao.index')->with('success', 'Plano enviado com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocorreu um erro ao enviar o plano.');
        }
    }
}
