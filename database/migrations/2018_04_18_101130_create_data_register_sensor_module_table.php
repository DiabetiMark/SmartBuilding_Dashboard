<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataRegisterSensorModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_register_sensor_module', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id');
            $table->integer('data_register_id');
            $table->integer('sensor_module_id');
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
        Schema::dropIfExists('data_register_sensor_module');
    }
}
