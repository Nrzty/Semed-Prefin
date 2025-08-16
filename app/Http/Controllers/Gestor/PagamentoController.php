<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Auth;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $escola = Auth::user()->escola;

        $repassesId = $escola->repasses->pluck("id");
        $pagamentos = Pagamento::whereIn('repasse_id', $repassesId)->orderBy('data_pagamento_efetivo', 'desc')->paginate(15);

        return view('gestor.pagamentos.index', compact('pagamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gestor.pagamentos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nome_fornecedor' => 'required|string|max:255',
            'cnpj_cpf_fornecedor' => 'required|string|max:14',
            'tipo_despesa' => 'required|in:Material de Custeio,Prestação de Serviço,Material de Capital',
            'numero_nota_fiscal' => 'required|string|max:50',
            'data_emissao_documento' => 'required|date',
            'data_pagamento_efetivo' => 'required|date',
            'numero_cheque' => 'nullable|string|max:50',
            'data_vencimento_cheque' => 'nullable|date',
            'valor_total_pagamento' => 'required|numeric|min:0.01',
        ]);

        $escola = Auth::user()->escola;

        $repasseAtivo = $escola->repasses()->where('status', 'Aberto')->latest()->first();

        if (!$repasseAtivo){
            return redirect()->back()->withErrors(['geral' => 'Não há um repasse ativo para lançar este pagamento.']);
        }

        $pagamento = new Pagamento();

        $pagamento->fill($validatedData);
        $pagamento->repasse_id = $repasseAtivo->id;
        $pagamento->save();

        return redirect()->route('gestor.dashboard')->with('success','Pagamento cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagamento $pagamento)
    {
        return view('gestor.pagamentos.edit', ['pagamento' => $pagamento]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pagamento $pagamento)
    {
        $validatedData = $request->validate([
            'nome_fornecedor' => 'required|string|max:255',
            'cnpj_cpf_fornecedor' => 'required|string|max:14',
            'tipo_despesa' => 'required|in:Material de Custeio,Prestação de Serviço,Material de Capital',
            'numero_nota_fiscal' => 'required|string|max:50',
            'data_emissao_documento' => 'required|date',
            'data_pagamento_efetivo' => 'required|date',
            'numero_cheque' => 'required|string|max:50',
            'data_vencimento_cheque' => 'required|date',
            'valor_total_pagamento' => 'required|numeric|min:0.01',
        ]);

        $pagamento->update($validatedData);
        return redirect()->route('gestor.dashboard')->with('success','Pagamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pagamento $pagamento)
    {   
        $pagamento->delete();

        return redirect()->route('gestor.pagamentos.index')->with('success','Pagamento excluido com sucesso!');
    }
}
