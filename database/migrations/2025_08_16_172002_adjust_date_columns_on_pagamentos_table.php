<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->renameColumn('data_pagamento','data_emissao_documento');
            $table->date('data_pagamento_efetivo')->after('data_emissao_documento');
            $table->date('data_vencimento_cheque')->after('numero_cheque')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn('data_vencimento_cheque');
            $table->dropColumn('data_pagamento_efetivo');
            $table->renameColumn('data_emissao_documento', 'data_pagamento');
        });
    }
};
