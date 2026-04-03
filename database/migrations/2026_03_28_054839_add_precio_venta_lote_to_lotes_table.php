<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('lotes', function (Blueprint $table) {
        // Lo ponemos después del costo_unitario para mantener orden
        $table->decimal('precio_venta_lote', 10, 2)->after('costo_unitario')->nullable();
    });
}

public function down(): void {
    Schema::table('lotes', function (Blueprint $table) {
        $table->dropColumn('precio_venta_lote');
    });
}
};
