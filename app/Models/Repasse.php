<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repasse extends Model
{
        
    public function escola(){
        return $this->belongsTo(Escola::class);
    }

    public function pagamentos(){
        return $this->hasMany(Pagamento::class);
    }

    public function totalGastoCusteio(){
        return $this->pagamentos()
                    ->whereIn('tipo_despesa', ['Material de Custeio', 'Prestação de Serviço'])
                    ->sum('valor_total_pagamento');
    }

    public function totalGastoCapital(){
        return $this->pagamentos()
                    ->whereIn('tipo_despesa', ['Material de Capital'])
                    ->sum('valor_total_pagamento');
    }
}
