<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{

    use HasFactory;
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

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data_emissao_documento' => 'datetime',
        'data_pagamento_efetivo' => 'datetime',
        'data_vencimento_cheque' => 'datetime',
    ];

    public function documentosKitDespesas()
    {
        return $this->hasMany(PrestacaoContaDocumento::class);
    }

    public function repasse(){
        return $this->belongsTo(Repasse::class);
    }
}
