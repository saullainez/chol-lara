<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_module', function (Blueprint $table) {
            $table->id();
            $table->string("module_name", 5); //SALG nombre del módulo al que se le asignará el permiso
            $table->string("rol_prefix", 7); //SALG prefijo del rol al que se le asignará el permiso
            $table->string('estatus',1); //SALG estatus del permiso
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
        Schema::dropIfExists('rol_module');
    }
}
