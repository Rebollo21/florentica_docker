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
    Schema::create('mermas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
        $table->integer('cantidad');
        $table->decimal('costo_perdido', 10, 2); // cantidad * precio_compra del lote
        $table->string('motivo')->nullable(); // Ej: "Marchitamiento", "Daño en transporte"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mermas');
    }
};
