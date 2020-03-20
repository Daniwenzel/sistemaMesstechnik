<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Messtechnik\Models\Company;
use Messtechnik\Models\WindFarm;

class CreateParqueEolicosTable extends Migration
{
    public function up()
    {
        Schema::create('parque_eolicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('cod_EPE');
            $table->unsignedBigInteger('empresa_id');
            $table->timestamps();

            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas');
        });

        $votorantimId = Company::where('nome', 'Votorantim Energia')->pluck('id')->first();
        $ceeeId = Company::where('nome', 'CEEE Distribuição')->pluck('id')->first();
        $odebrechtId = Company::where('nome', 'Odebrecht Group')->pluck('id')->first();
        $rebId = Company::where('nome', 'REB Cubico')->pluck('id')->first();
        $eletrosulId = Company::where('nome', 'Eletrosul')->pluck('id')->first();

        $parques = array(
            [
                'nome' => 'Ventos Aragano I',
                'cod_EPE' => '148-571',
                'empresa_id' => $odebrechtId
            ],
            [
                'nome' => 'Corredor do Senandes II',
                'cod_EPE' => '127-568',
                'empresa_id' => $odebrechtId
            ],
            [
                'nome' => 'Corredor do Senandes III',
                'cod_EPE' => '149-569',
                'empresa_id' => $odebrechtId
            ],
            [
                'nome' => 'Corredor do Senandes IV',
                'cod_EPE' => '142-570',
                'empresa_id' => $odebrechtId
            ],
            [
                'nome' => 'EOL-CGE REB CASSINO II',
                'cod_EPE' => '179',
                'empresa_id' => $rebId
            ],
            [
                'nome' => 'EOL-CGE REB CASSINO III',
                'cod_EPE' => '180',
                'empresa_id' => $rebId
            ],
            [
                'nome' => 'EOL-CGE REB CASSINO I',
                'cod_EPE' => '178',
                'empresa_id' => $rebId
            ],
            [
                'nome' => 'Fazenda Vera Cruz',
                'cod_EPE' => '474',
                'empresa_id' => $ceeeId
            ],
            [
                'nome' => 'Curupira',
                'cod_EPE' => '473',
                'empresa_id' => $ceeeId
            ],
            [
                'nome' => 'Povo Novo',
                'cod_EPE' => '475',
                'empresa_id' => $ceeeId
            ],
            [
                'nome' => 'Ventos de São Vicente 08',
                'cod_EPE' => '575',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de São Vicente 09',
                'cod_EPE' => '576',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de São Vicente 10',
                'cod_EPE' => '577',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de São Vicente 11',
                'cod_EPE' => '578',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de São Vicente 12',
                'cod_EPE' => '579',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de São Vicente 13',
                'cod_EPE' => '580',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de São Vicente 14',
                'cod_EPE' => '581',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de Santo Augusto VIII',
                'cod_EPE' => '486',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos de Santo Estevão III',
                'cod_EPE' => '487',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Ventos da Bahia VIII',
                'cod_EPE' => '740001 - 537',
                'empresa_id' => $votorantimId
            ],
            [
                'nome' => 'Chuí 09',
                'cod_EPE' => '321',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 24',
                'cod_EPE' => '358',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 25',
                'cod_EPE' => '330',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 26',
                'cod_EPE' => '359',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 27',
                'cod_EPE' => '333',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 28',
                'cod_EPE' => '331',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 29',
                'cod_EPE' => '322',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 30',
                'cod_EPE' => '360',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 31',
                'cod_EPE' => '332',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 34',
                'cod_EPE' => '361',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 35',
                'cod_EPE' => '362',
                'empresa_id' => $eletrosulId
            ],
            [
                'nome' => 'Verace 36',
                'cod_EPE' => '334',
                'empresa_id' => $eletrosulId
            ]
        );

        foreach ($parques as $parque) {
            WindFarm::create($parque);
        }
    }

    public function down()
    {
        Schema::dropIfExists('parque_eolicos');
    }
}
