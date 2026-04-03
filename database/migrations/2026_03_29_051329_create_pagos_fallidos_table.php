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
    Schema::create('pagos_fallidos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained('users');
        $table->decimal('monto', 10, 2);
        $table->string('error_mensaje'); // El mensaje humano: "Fondos insuficientes"
        $table->string('error_codigo')->nullable(); // El código técnico de Stripe
        $table->json('carrito_snapshot'); // Guardamos qué quería comprar para recuperarlo
        $table->string('email_cliente')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_fallidos');
    }
};
