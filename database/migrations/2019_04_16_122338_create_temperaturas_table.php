<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemperaturasTable extends Migration
{
    public function up()
    {
        Schema::create('temperaturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->double('leitura');
            $table->unsignedBigInteger('sensor_id');
            $table->timestamps();

            $table->foreign('sensor_id')
                ->references('id')
                ->on('sensors');
        });
    }

    public function down()
    {
        Schema::dropIfExists('temperaturas');
    }
}
