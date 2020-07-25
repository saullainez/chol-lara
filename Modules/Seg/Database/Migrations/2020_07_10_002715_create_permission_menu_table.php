<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_menu', function (Blueprint $table) {
            $table->id();
            $table->string("mod_prefix", 5); //SALG prefijo del módulo
            $table->integer("sub_menu_id"); //SALG id del sub menú
            $table->string("rol_prefix", 7); //SALG prefijo del rol
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
        Schema::dropIfExists('permission_menu');
    }
}
