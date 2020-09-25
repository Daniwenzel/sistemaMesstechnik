<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site', function (Blueprint $table) {
            $table->integer('codigo'); // == CODIGO NO FIREBIRD
            $table->integer('clicodigo')->nullable();
            $table->string('nomemms',60)->nullable();
            $table->string('dessite',60)->nullable();
            $table->string('latsite',10)->nullable();
            $table->string('lngsite',10)->nullable();
            $table->string('infsite',30)->nullable();
            $table->smallInteger('tznsite')->nullable();
            $table->string('sitename',64)->nullable();
            $table->char('estacao',6)->nullable();
            $table->integer('hrsenvio')->nullable();
            $table->dateTime('ultenvio')->nullable();
            $table->string('emlenvio01',64)->nullable();
            $table->string('emlenvio02',64)->nullable();
            $table->string('emlenvio03',64)->nullable();
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
        Schema::dropIfExists('site');
    }
}
