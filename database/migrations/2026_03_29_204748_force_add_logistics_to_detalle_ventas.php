<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            // Validamos si no existen ya (por si las dudas)
            if (!Schema::hasColumn('detalle_ventas', 'qr_token')) {
                $table->string('qr_token')->unique()->nullable()->after('estado');
                $table->enum('status_entrega', [
                    'preparacion', 
                    'en_ruta', 
                    'entregado', 
                    'cancelado'
                ])->default('preparacion')->after('qr_token');
                $table->timestamp('entregado_at')->nullable()->after('status_entrega');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'status_entrega', 'entregado_at']);
        });
    }
};