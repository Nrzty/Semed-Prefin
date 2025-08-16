<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repasse extends Model
{

    public function escola(){
        return $this->hasMany(Escola::class);
    }

    public function pagamentos(){
        return $this->hasMany(Pagamento::class);
    }
}
