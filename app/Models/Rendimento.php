<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rendimento extends Model
{
    public function repasses(): HasOne
    {
        return $this->hasOne(Repasse::class);
    }
}
