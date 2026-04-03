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
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); // bigint (PK)
            
            // Datos comerciales
            $table->string('nombre_ramo', 100);
            $table->text('descripcion')->nullable();
            
            // Finanzas (Decimal es obligatorio para dinero)
            $table->decimal('precio_venta', 10, 2); 
            
            // Organización y Filtros
            $table->string('categoria', 50); // Ej: "rosas", "tulipanes"
            $table->string('imagen_url', 255)->nullable();
            
            // Estado del producto (CEO Control)
            $table->boolean('activo')->default(true); 
            
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};