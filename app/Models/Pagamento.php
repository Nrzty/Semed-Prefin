<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $fillable = [
        'nome_fornecedor',
        'cnpj_cpf_fornecedor',
        'tipo_despesa',
        'numero_nota_fiscal',
        'data_emissao_documento',
        'data_pagamento_efetivo',
        'numero_cheque',
        'data_vencimento_cheque',
        'valor_total_pagamento',
    ];

    public function repasse(){
        return $this->belongsTo(Repasse::class);
    }
}
