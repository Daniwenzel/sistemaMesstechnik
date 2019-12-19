<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClienteTable extends Migration
{
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->bigIncrements('codigo');
            $table->string('razaosocial')->unique();
            $table->string('endereco')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cliente');
    }
}
