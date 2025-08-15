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
        Schema::create('repasses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained()->onDelete('restrict');
            $table->unsignedTinyInteger('numero_parcela')->default(0);
            $table->year('ano_exercicio');
            $table->enum('status', ['Aberto', 'Finalizado'])->default('Aberto');
            $table->decimal('valor_custeio', 12, 2)->default(0);
            $table->decimal('valor_capital', 12, 2)->default(0);
            $table->date('data_repasse');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repasses');
    }
};
