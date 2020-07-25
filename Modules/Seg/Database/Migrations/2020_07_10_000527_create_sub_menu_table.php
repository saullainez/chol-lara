<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_menu', function (Blueprint $table) {
            $table->id();
            $table->integer('id_menu'); //SALG Id del menú al que pertenece el sub menú
            $table->string("description", 40); //SALG descripción del sub menú
            $table->string("icon", 30); //SALG ícono del sub menú
            $table->string("uri", 30); //SALG uri del sub menú
            $table->string("created_by", 15); //SALG usuario que crea el registro
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
        Schema::dropIfExists('sub_menu');
    }
}
