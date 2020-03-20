<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('tipo');
            $table->unsignedBigInteger('torre_id');
            $table->timestamps();

            $table->foreign('torre_id')
                ->references('id')
                ->on('torres');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensors');
    }
}
