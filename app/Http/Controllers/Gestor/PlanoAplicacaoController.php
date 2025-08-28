<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\PlanoAplicacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PlanoAplicacaoController extends Controller
{
    public function create()
    {
        return view('gestor.planos-aplicacao.create');
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

        try {
            DB::beginTransaction();

            $plano = PlanoAplicacao::create([
                'escola_id' => Auth::user()->escola_id,
                'user_id' => Auth::id(),
                'status' => 'Em Análise',
            ]);

            foreach ($itens as $itemData) {
                $plano->itens()->create([
                    'descricao' => $itemData['descricao'],
                    'categoria_despesa' => $itemData['categoria_despesa'],
                    'unidade' => $itemData['unidade'],
                    'quantidade' => $itemData['quantidade'],
                    'valor_unitario' => $itemData['valor_unitario'],
                ]);

                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors(['geral' => 'Ocorreu um erro ao salvar o plano, confira os detalhes: '.$e->getMessage()]);
        }

        return view('gestor.planos-aplicacao.store');
    }
}
