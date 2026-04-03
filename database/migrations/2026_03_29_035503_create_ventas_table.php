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
    Schema::create('ventas', function (Blueprint $table) {
        $table->id();
        // Relación con el usuario (si no hay sesión, puede ser null)
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        
        $table->decimal('total', 10, 2);
        $table->string('pago_id')->unique(); // ID de Stripe (ch_...)
        $table->string('metodo_pago')->default('Tarjeta');
        $table->string('estatus')->default('Pagado'); // Pagado, Pendiente, Cancelado
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
