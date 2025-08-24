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
        Schema::create('itens_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pagamento_id')
                  ->constrained('pagamentos')
                  ->onDelete('cascade');

            $table->string('descricao');
            $table->enum('categoria_despesa', ['Custeio', 'Capital']);
            $table->string('unidade', 50)->default('UND');
            $table->integer('quantidade')->unsigned();
            $table->decimal('valor_unitario', 10, 2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_pagamentos');
    }
};
