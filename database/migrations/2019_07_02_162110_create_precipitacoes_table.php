<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrecipitacoesTable extends Migration
{
    public function up()
    {
        Schema::create('precipitacoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->double('leitura')->nullable();
            $table->unsignedBigInteger('sensor_id');
            $table->timestamps();

            $table->foreign('sensor_id')
                ->references('id')
                ->on('sensors');
        });
    }

    public function down()
    {
        Schema::dropIfExists('precipitacoes');
    }
}
