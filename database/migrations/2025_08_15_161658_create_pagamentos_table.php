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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repasse_id')->constrained('repasses')->onDelete('restrict');
            $table->string('nome_fornecedor');
            $table->string('cnpj_cpf_fornecedor');
            $table->enum('tipo_despesa', ['Material de Custeio', 'Prestação de Serviço', 'Material de Capital']);
            $table->string('numero_nota_fiscal', 20);
            $table->date('data_pagamento');
            $table->string('numero_cheque', 50);
            $table->decimal('valor_total_pagamento', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
