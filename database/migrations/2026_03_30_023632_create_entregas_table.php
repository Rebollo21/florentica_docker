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
    Schema::create('entregas', function (Blueprint $table) {
        $table->id();
        // Relación con la venta
        $table->unsignedBigInteger('detalle_venta_id')->unique(); 
        // Relación con el repartidor
        $table->unsignedBigInteger('repartidor_id')->nullable();
        
        // Lógica de Negocio
        $table->string('status')->default('preparacion'); // preparacion, en_ruta, entregado, cancelado
        $table->string('codigo_qr')->nullable(); // El token que debe validar
        $table->enum('vehiculo_usado', ['moto', 'carro'])->nullable();
        
        // Tiempos
        $table->timestamp('fecha_salida')->nullable();
        $table->timestamp('fecha_entrega_real')->nullable();
        $table->timestamps();

        // Llaves foráneas
        $table->foreign('detalle_venta_id')->references('id')->on('detalle_ventas')->onDelete('cascade');
        $table->foreign('repartidor_id')->references('id')->on('users');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
