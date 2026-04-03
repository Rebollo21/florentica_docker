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
            // Eliminamos la columna redundante
            if (Schema::hasColumn('detalle_ventas', 'direccion')) {
                $table->dropColumn('direccion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            // Por si necesitas deshacer la migración, la volvemos a agregar
            $table->text('direccion')->nullable()->after('municipio');
        });
    }
};