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
    Schema::create('comments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relaciona con tu tabla de usuarios
        $table->unsignedTinyInteger('stars'); // Para las estrellas (1 a 5)
        $table->text('comment'); // El texto del comentario
        $table->string('photo')->nullable(); // Ruta de la foto (es opcional)
        $table->timestamps(); // Crea 'created_at' y 'updated_at'
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
