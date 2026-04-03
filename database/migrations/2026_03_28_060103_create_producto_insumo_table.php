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
    Schema::create('producto_insumo', function (Blueprint $table) {
        $table->id();
        // Relación con Productos
        $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
        // Relación con Insumos
        $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
        // Columna extra para saber cuántos de ese insumo lleva el producto
        $table->decimal('cantidad', 10, 2); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_insumo');
    }
};
