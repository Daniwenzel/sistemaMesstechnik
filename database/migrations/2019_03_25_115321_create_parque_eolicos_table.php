<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

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


        DB::table('parque_eolicos')->insert(
            array(
                [
                    'id' => 1,
                    'nome' => 'Ventos Aragano I',
                    'cod_EPE' => '148-571',
                    'empresa_id' => $odebrechtId
                ],
                [
                    'id' => 2,
                    'nome' => 'Corredor do Senandes II',
                    'cod_EPE' => '127-568',
                    'empresa_id' => $odebrechtId
                ],
                [
                    'id' => 3,
                    'nome' => 'Corredor do Senandes III',
                    'cod_EPE' => '149-569',
                    'empresa_id' => $odebrechtId
                ],
                [
                    'id' => 4,
                    'nome' => 'Corredor do Senandes IV',
                    'cod_EPE' => '142-570',
                    'empresa_id' => $odebrechtId
                ],
                [
                    'id' => 5,
                    'nome' => 'EOL-CGE REB CASSINO II',
                    'cod_EPE' => '179',
                    'empresa_id' => $rebId
                ],
                [
                    'id' => 6,
                    'nome' => 'EOL-CGE REB CASSINO III',
                    'cod_EPE' => '180',
                    'empresa_id' => $rebId
                ],
                [
                    'id' => 7,
                    'nome' => 'EOL-CGE REB CASSINO I',
                    'cod_EPE' => '178',
                    'empresa_id' => $rebId
                ],
                [
                    'id' => 8,
                    'nome' => 'Fazenda Vera Cruz',
                    'cod_EPE' => '474',
                    'empresa_id' => $ceeeId
                ],
                [
                    'id' => 9,
                    'nome' => 'Curupira',
                    'cod_EPE' => '473',
                    'empresa_id' => $ceeeId
                ],
                [
                    'id' => 10,
                    'nome' => 'Povo Novo',
                    'cod_EPE' => '475',
                    'empresa_id' => $ceeeId
                ],
                [
                    'id' => 11,
                    'nome' => 'Ventos de São Vicente 08',
                    'cod_EPE' => '575',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 12,
                    'nome' => 'Ventos de São Vicente 09',
                    'cod_EPE' => '576',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 13,
                    'nome' => 'Ventos de São Vicente 10',
                    'cod_EPE' => '577',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 14,
                    'nome' => 'Ventos de São Vicente 11',
                    'cod_EPE' => '578',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 15,
                    'nome' => 'Ventos de São Vicente 12',
                    'cod_EPE' => '579',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 16,
                    'nome' => 'Ventos de São Vicente 13',
                    'cod_EPE' => '580',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 17,
                    'nome' => 'Ventos de São Vicente 14',
                    'cod_EPE' => '581',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 18,
                    'nome' => 'Ventos de Santo Augusto VIII',
                    'cod_EPE' => '486',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 19,
                    'nome' => 'Ventos de Santo Estevão III',
                    'cod_EPE' => '487',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 20,
                    'nome' => 'Ventos da Bahia VIII',
                    'cod_EPE' => '740001 - 537',
                    'empresa_id' => $votorantimId
                ],
                [
                    'id' => 21,
                    'nome' => 'Chuí 09',
                    'cod_EPE' => '321',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 22,
                    'nome' => 'Verace 24',
                    'cod_EPE' => '358',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 23,
                    'nome' => 'Verace 25',
                    'cod_EPE' => '330',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 24,
                    'nome' => 'Verace 26',
                    'cod_EPE' => '359',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 25,
                    'nome' => 'Verace 27',
                    'cod_EPE' => '333',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 26,
                    'nome' => 'Verace 28',
                    'cod_EPE' => '331',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 27,
                    'nome' => 'Verace 29',
                    'cod_EPE' => '322',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 28,
                    'nome' => 'Verace 30',
                    'cod_EPE' => '360',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 29,
                    'nome' => 'Verace 31',
                    'cod_EPE' => '332',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 30,
                    'nome' => 'Verace 34',
                    'cod_EPE' => '361',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 31,
                    'nome' => 'Verace 35',
                    'cod_EPE' => '362',
                    'empresa_id' => $eletrosulId
                ],
                [
                    'id' => 32,
                    'nome' => 'Verace 36',
                    'cod_EPE' => '334',
                    'empresa_id' => $eletrosulId
                ]
            )
        );
    }

    public function down()
    {
        Schema::dropIfExists('parque_eolicos');
    }
}
