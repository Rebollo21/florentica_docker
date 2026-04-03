<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('lotes', function (Blueprint $table) {
        // Agregamos el costo de lo que tú pagas al proveedor
        $table->decimal('costo_unitario', 10, 2)->after('cantidad_actual');
        // Agregamos la fecha en que recibes el paquete
        $table->date('fecha_llegada')->after('costo_unitario');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotes', function (Blueprint $table) {
            //
        });
    }
};
