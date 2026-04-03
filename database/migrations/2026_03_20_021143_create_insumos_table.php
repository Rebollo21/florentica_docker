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
    Schema::create('insumos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_insumo');
        // Usamos enum para que solo acepte estos dos tipos
        $table->enum('tipo', ['flor', 'materia_prima', 'accesorio'])->default('flor');
        $table->string('unidad_medida'); // Tallo, Metro, Pieza
        $table->integer('stock_actual')->default(0);
        $table->decimal('precio_unitario', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
