<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUmidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umidades', function (Blueprint $table) {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('umidades');
    }
}
