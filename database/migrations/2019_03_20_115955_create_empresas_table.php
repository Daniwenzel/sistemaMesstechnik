<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Messtechnik\Models\Company;

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

        $empresas = array('Messtechnik', 'Votorantim Energia', 'EDF', 'CEEE Distribuição',
            'REB Cubico', 'Odebrecht Group', 'Desenvix Energias Renováveis S.A.', 'Eletrosul');

        foreach ($empresas as $empresa) {
            $empresa = new Company([
                'nome' => $empresa
            ]);
            $empresa->save();
        }
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
