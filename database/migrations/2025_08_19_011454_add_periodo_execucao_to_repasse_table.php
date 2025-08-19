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
        Schema::table('repasses', function (Blueprint $table) {
            $table->date('inicio_execucao')->after('data_repasse')->default(now());
            $table->date('fim_execucao')->after('inicio_execucao')->default(now());
        });
    }

    /**
     * Reverse the migrations.s
     */
    public function down(): void
    {
        Schema::table('repasses', function (Blueprint $table) {
            $table->dropColumn('inicio_execucao');
            $table->dropColumn('fim_execucao');
        });
    }
};
