<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoAplicacaoItem extends Model
{
    use HasFactory;
    protected $table = 'planos_aplicacao_itens';
    protected $fillable = [
        'plano_aplicacao_id',
        'descricao',
        'categoria_despesa',
        'unidade',
        'quantidade',
        'valor_unitario',
    ];

    public function planoAplicacao()
    {
        return $this->belongsTo(PlanoAplicacao::class);
    }

    protected function valorTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->quantidade * $this->valor_unitario,
        );
    }
}
