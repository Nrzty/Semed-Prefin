<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPagamento extends Model
{

    protected $table = 'itens_pagamentos';

    protected $fillable = [
        'pagamento_id',
        'descricao',
        'categoria_despesa',
        'unidade',
        'quantidade',
        'valor_unitario',
    ];

    public function pagamento(): BelongsTo
    {
        return $this->belongsTo(Pagamento::class);
    }

    protected function valorTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantidade * $this->valor_unitario
        );
    }
}
