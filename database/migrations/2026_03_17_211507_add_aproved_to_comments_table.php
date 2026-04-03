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
    Schema::table('comments', function (Blueprint $table) {
        // Creamos la columna booleana. 
        // default(0) significa que todo comentario nuevo nace "oculto".
        $table->boolean('approved')->default(0)->after('comment'); 
    });
}

public function down()
{
    Schema::table('comments', function (Blueprint $table) {
        $table->dropColumn('approved');
    });
}
};
