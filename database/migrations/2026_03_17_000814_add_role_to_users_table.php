<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Esta función se ejecuta cuando haces 'php artisan migrate'.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregamos la columna role. 
            // Usamos 'after' para que en HeidiSQL sea fácil de leer junto al email.
            $table->string('role')->default('buyer')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     * Esta función se ejecuta si alguna vez haces 'php artisan migrate:rollback'.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Si nos arrepentimos, eliminamos la columna creada.
            $table->dropColumn('role');
        });
    }
};