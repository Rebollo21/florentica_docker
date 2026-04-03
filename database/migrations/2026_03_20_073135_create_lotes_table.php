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
    Schema::create('lotes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
        $table->integer('cantidad_inicial');
        $table->integer('cantidad_actual');
        $table->integer('vida_flor_dias')->nullable();
        $table->timestamp('fecha_vencimiento')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
