<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArquivoLogsTable extends Migration
{
    public function up()
    {
        Schema::create('arquivo_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('diretorio');
            $table->string('status');
            $table->string('mensagem');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arquivo_logs');
    }
}
