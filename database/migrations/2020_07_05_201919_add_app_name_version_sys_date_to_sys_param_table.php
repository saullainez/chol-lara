<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppNameVersionSysDateToSysParamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sys_param', function (Blueprint $table) {
            $table->string('app_name', 50);
            $table->string('version', 10);
            $table->date('sys_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sys_param', function (Blueprint $table) {
            //
        });
    }
}
