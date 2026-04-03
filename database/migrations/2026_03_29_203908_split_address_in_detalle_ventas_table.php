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
            // Campos separados para una logística tipo Uber/Rappi
            $table->string('calle')->after('nombre_receptor')->nullable();
            $table->string('num_ext', 20)->after('calle')->nullable();
            $table->string('num_int', 20)->after('num_ext')->nullable();
            $table->string('colonia')->after('num_int')->nullable();
            $table->string('codigo_postal', 10)->after('colonia')->nullable();
            $table->string('municipio')->after('codigo_postal')->nullable();
            $table->string('estado')->default('Estado de México')->after('municipio');
            
            // El campo 'direccion' original lo podemos renombrar o dejar como respaldo
            // $table->renameColumn('direccion', 'direccion_completa_respaldo');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropColumn(['calle', 'num_ext', 'num_int', 'colonia', 'codigo_postal', 'municipio', 'estado']);
        });
    }
};
