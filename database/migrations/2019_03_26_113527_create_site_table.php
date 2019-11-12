<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorresTable extends Migration
{
    public function up()
    {
        Schema::create('site', function (Blueprint $table) {
            $table->bigIncrements('codigo');
            $table->string('dessite');
            $table->string('latsite');
            $table->string('lngsite');
            $table->string('infsite');
            $table->string('tznsite');
            $table->string('sitename');
            $table->string('estacao');
            $table->integer('hrsenvio');
            $table->timestamp('ultenvio');
            $table->string('emlenvio01');
            $table->string('emlenvio02');
            $table->string('emlenvio03');

            $table->unsignedBigInteger('clicodigo');
            $table->timestamps();
            $table->foreign('clicodigo')
                ->references('codigo')
                ->on('cliente');
        });
    }

    public function down()
    {
        Schema::dropIfExists('site');
    }
}
