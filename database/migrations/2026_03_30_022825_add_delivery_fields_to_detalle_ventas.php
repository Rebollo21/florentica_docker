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
        // Añadimos status_entrega si no existe
        if (!Schema::hasColumn('detalle_ventas', 'status_entrega')) {
            $table->string('status_entrega')->default('preparacion')->after('id');
        }
        
        // Esta es la que te está dando el error 1054
        if (!Schema::hasColumn('detalle_ventas', 'fecha_entrega')) {
            $table->timestamp('fecha_entrega')->nullable()->after('status_entrega');
        }
    });
}

public function down(): void
{
    Schema::table('detalle_ventas', function (Blueprint $table) {
        $table->dropColumn(['status_entrega', 'fecha_entrega']);
    });
}
};
