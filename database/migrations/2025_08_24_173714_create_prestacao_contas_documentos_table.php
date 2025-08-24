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
        Schema::create('prestacao_contas_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repasse_id')->constrained('repasses')->onDelete('cascade');
            $table->foreignId('pagamento_id')->constrained('pagamentos')->onDelete('cascade');

            $table->string('tipo_documento');
            $table->string('caminho_arquivo');
            $table->string('nome_original');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestacao_contas_documentos');
    }
};
