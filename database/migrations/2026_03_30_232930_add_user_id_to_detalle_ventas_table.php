<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $row) {
            // Agregamos la columna user_id después de la ID principal
            // constrained() busca automáticamente la tabla 'users'
            // onDelete('cascade') borra los detalles si el usuario se elimina
            $row->foreignId('user_id')
                ->after('id')
                ->nullable() 
                ->constrained('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};