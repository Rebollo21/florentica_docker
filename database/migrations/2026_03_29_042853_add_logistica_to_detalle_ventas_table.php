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
        Schema::table('detalle_ventas', function (Blueprint $table) {
            // Agregamos los campos necesarios para el repartidor de Florentica
            $table->string('nombre_receptor')->nullable()->after('subtotal');
            $table->string('telefono', 20)->nullable()->after('nombre_receptor');
            $table->text('direccion')->nullable()->after('telefono');
            $table->text('referencias')->nullable()->after('direccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropColumn(['nombre_receptor', 'telefono', 'direccion', 'referencias']);
        });
    }
};