<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('codigo');
            $table->string('name');
            $table->string('email')->unique();
            $table->date('birthday')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('clicodigo');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
