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
        Schema::create('rendimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repasse_id')->unique()->constrained('repasses')->onDelete('cascade');
            $table->decimal('valor_rendimento',10,2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendimentos');
    }
};
