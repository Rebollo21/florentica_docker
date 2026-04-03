<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para crear la tabla de auditoría de lotes.
     */
    public function up(): void
    {
        Schema::create('detalle_venta_lotes', function (Blueprint $table) {
            $table->id();
            
            // Relación con la venta (Para saber qué cliente compró)
            $table->foreignId('venta_id')
                  ->constrained('ventas')
                  ->onDelete('cascade');

            // Relación con el lote específico (De dónde salió la flor)
            $table->foreignId('lote_id')
                  ->constrained('lotes')
                  ->onDelete('cascade');

            // Cantidad exacta que se tomó de ese lote
            $table->integer('cantidad_descontada');

            // Metadata para auditoría (Cuándo se hizo el movimiento)
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_venta_lotes');
    }
};