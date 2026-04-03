<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStockAndVidaFromInsumosTable extends Migration
{
    /**
     * Ejecuta las alteraciones en la tabla insumos.
     */
    public function up()
    {
        Schema::table('insumos', function (Blueprint $blueprint) {
            // ✂️ Cortamos por lo sano lo que ya no va en el catálogo maestro
            $blueprint->dropColumn(['stock_actual', 'vida_flor']);
        });
    }

    /**
     * Revierte los cambios (por si acaso).
     */
    public function down()
    {
        Schema::table('insumos', function (Blueprint $blueprint) {
            $blueprint->integer('stock_actual')->default(0);
            $blueprint->integer('vida_flor')->nullable();
        });
    }
}