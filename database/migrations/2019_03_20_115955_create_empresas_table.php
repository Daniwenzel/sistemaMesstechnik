<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateEmpresasTable extends Migration
{
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome')->unique();
            $table->string('cnpj')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        DB::table('empresas')->insert(
            array(
                [
                    'nome' => 'Messtechnik'
                ],
                [
                    'nome' => 'Votorantim Energia'
                ],
                [
                    'nome' => 'EDF'
                ],
                [
                    'nome' => 'CEEE Distribuição'
                ],
                [
                    'nome' => 'REB Cubico'
                ],
                [
                    'nome' => 'Odebrecht Group'
                ],
                [
                    'nome' => 'Desenvix Energias Renováveis S.A.'
                ],
                [
                    'nome' => 'Eletrosul'
                ]
                )
        );
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
