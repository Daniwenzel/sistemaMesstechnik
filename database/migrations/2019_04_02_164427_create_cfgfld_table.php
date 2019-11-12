<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    public function up()
    {
        Schema::create('cfgfld', function (Blueprint $table) {
            $table->bigIncrements('codigo');
            $table->string('fldname');
            $table->string('flddesc');
            $table->unsignedBigInteger('vrscodigo');

            $table->foreign('vrscodigo')
                ->references('codigo')
                ->on('cfgvrs');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cfgfld');
    }
}
