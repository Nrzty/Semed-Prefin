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
        Schema::create('planos_aplicacao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_aplicacao_id')->constrained('planos_aplicacao')->onDelete('cascade');
            $table->string('descricao');
            $table->enum('categoria_despesa', ['Custeio', 'Capital']);
            $table->string('unidade', 50)->default('UND');
            $table->integer('quantidade')->unsigned();
            $table->decimal('valor_unitario', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos_aplicacao_itens');
    }
};
