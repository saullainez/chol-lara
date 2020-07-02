<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->id();
            $table->string("name", 5); //SALG nombre del módulo
            $table->string("description", 40); //SALG descripción del módulo
            $table->string("icon", 30); //SALG ícono del módulo
            $table->string("color_text", 10); //SALG color del texto
            $table->string("background", 10); //SALG color del background
            $table->string("uri", 30); //SALG uri del módulo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
    }
}
