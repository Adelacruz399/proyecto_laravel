<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToHistorialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historiales', function (Blueprint $table) {
            // Asegúrate de que el campo dni en historiales sea del mismo tipo y longitud que en users
            $table->unsignedBigInteger('dni')->change();
            $table->foreign('dni')->references('dni')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('historiales', function (Blueprint $table) {
            $table->dropForeign(['dni']);
        });
    }
}
