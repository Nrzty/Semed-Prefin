<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestacaoContaDocumento extends Model
{
    use HasFactory;

    protected $table = 'prestacao_contas_documentos';
    protected $fillable =[
        'repasse_id',
        'pagamento_id',
        'tipo_documento',
        'path_arquivo',
        'nome_original',
    ];

    public function repasse()
    {
        return $this->belongsTo(Repasse::class);
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }

}
