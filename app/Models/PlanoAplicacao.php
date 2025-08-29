<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoAplicacao extends Model
{
    use HasFactory;

    protected $table = 'planos_aplicacao';

    protected $fillable = [
        'escola_id',
        'user_id',
        'status',
        'motivo_reprovacao',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(PlanoAplicacaoItem::class);
    }
}
