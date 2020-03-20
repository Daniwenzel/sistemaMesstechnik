<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorresTable extends Migration
{
    public function up()
    {
        Schema::create('torres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cod_MSTK');
            $table->string('cod_cliente');
            $table->string('cod_arquivo_dados')->nullable();
            $table->unsignedBigInteger('parque_id');
            $table->timestamps();
            $table->foreign('parque_id')
                ->references('id')
                ->on('parque_eolicos');
        });



        DB::table('torres')->insert(
            array(
                [
                    'cod_MSTK' => 'ODB5301',
                    'cod_cliente' => 'VA1',
                    'cod_arquivo_dados' => 'VA1 - Vento Aragano 01(ODB5301)',
                    'parque_id' => 1 // Ventos Aragano I
                ],
                [
                    'cod_MSTK' => 'ODB5302',
                    'cod_cliente' => 'S02',
                    'cod_arquivo_dados' => 'S02 - Senandes 02(ODB5302)',
                    'parque_id' => 2 // Corredor do Senandes II
                ],
                [
                    'cod_MSTK' => 'ODB5303',
                    'cod_cliente' => 'S03',
                    'cod_arquivo_dados' => 'S03 - Senandes 03(ODB5303)',
                    'parque_id' => 3 // Corredor do Senandes III
                ],
                [
                    'cod_MSTK' => 'ODB5304',
                    'cod_cliente' => 'S04',
                    'cod_arquivo_dados' => 'S04 - Senandes 04(ODB5304)',
                    'parque_id' => 4 // Corredor do Senandes IV
                ],
                [
                    'cod_MSTK' => 'REB5303',
                    'cod_cliente' => 'Vento',
                    'cod_arquivo_dados' => 'REB5303 - TORRE VENTO - AMA(REB5303)',
                    'parque_id' => 5 // EOL-CGE REB CASSINO II
                ],
                [
                    'cod_MSTK' => 'REB5302',
                    'cod_cliente' => 'Brisa',
                    'cod_arquivo_dados' => 'REB5302 - TORRE BRISA - AMA(REB5302)',
                    'parque_id' => 6 // EOL-CGE REB CASSINO III
                ],
                [
                    'cod_MSTK' => 'REB5301',
                    'cod_cliente' => 'Wind',
                    'cod_arquivo_dados' => 'REB5301 - TORRE BRISA - AMA(REB5301)',
                    'parque_id' => 7 // EOL-CGE REB CASSINO I
                ],
                [
                    'cod_MSTK' => 'CEPV5301',
                    'cod_cliente' => 'N/A',
                    'cod_arquivo_dados' => 'CEPV5301 - TORRE VERA CRUZ - AMA(CEPV5301)',
                    'parque_id' => 8 // Fazenda Vera Cruz
                ],
                [
                    'cod_MSTK' => 'CEPV5302',
                    'cod_cliente' => 'N/A',
                    'cod_arquivo_dados' => 'CEPV5302 - TORRE VENTOS DO CURUPIRA - AMA(CEPV5302)',
                    'parque_id' => 9 // Curupira
                ],
                [
                    'cod_MSTK' => 'CEPV5303',
                    'cod_cliente' => 'N/A',
                    'cod_arquivo_dados' => 'CEPV5303 - TORRE POVO NOVO - AMA(CEPV5303)',
                    'parque_id' => 10 // Povo Novo
                ],
                [
                    'cod_MSTK' => 'CEVP8908',
                    'cod_cliente' => 'MAST-08',
                    'cod_arquivo_dados' => 'CEVP-8908- MAST-08 - Torre AMA(CEVP8908)',
                    'parque_id' => 11 // Ventos de São Vicente 08
                ],
                [
                    'cod_MSTK' => 'CEVP8909',
                    'cod_cliente' => 'MAST-09',
                    'cod_arquivo_dados' => 'CEVP-8909- MAST-09 - Torre AMA(CEVP8909)',
                    'parque_id' => 12 // Ventos de São Vicente 09
                ],
                [
                    'cod_MSTK' => 'CEVP8910',
                    'cod_cliente' => 'MAST-10',
                    'cod_arquivo_dados' => 'CEVP-8910- MAST-10 - Torre AMA(CEVP8910)',
                    'parque_id' => 13 // Ventos de São Vicente 10
                ],
                [
                    'cod_MSTK' => 'CEVP8911',
                    'cod_cliente' => 'MAST-11',
                    'cod_arquivo_dados' => 'CEVP-8911- MAST-11 - Torre AMA(CEVP8911)',
                    'parque_id' => 14 // Ventos de São Vicente 11
                ],
                [
                    'cod_MSTK' => 'CEVP8912',
                    'cod_cliente' => 'MAST-12',
                    'cod_arquivo_dados' => 'CEVP-8912- MAST-12 - Torre AMA - PCT-VIC12(CEVP8912)',
                    'parque_id' => 15 // Ventos de São Vicente 12
                ],
                [
                    'cod_MSTK' => 'CEVP8913',
                    'cod_cliente' => 'MAST-13',
                    'cod_arquivo_dados' => 'CEVP-8913- MAST-13 - Torre AMA(CEVP8913)',
                    'parque_id' => 16 // Ventos de São Vicente 13
                ],
                [
                    'cod_MSTK' => 'CEVP8914',
                    'cod_cliente' => 'MAST-14',
                    'cod_arquivo_dados' => 'CEVP-8914- MAST-14 - Torre AMA - PCT-VIC14(CEVP8914)',
                    'parque_id' => 17 // Ventos de São Vicente 14
                ],
                [
                    'cod_MSTK' => 'CEVA8702',
                    'cod_cliente' => 'VA-8738',
                    'cod_arquivo_dados' => 'CEVA8702-Ventos do Araripe VA8738(CEVA8702)',
                    'parque_id' => 18 // Ventos de Santo Augusto VIII
                ],
                [
                    'cod_MSTK' => 'CEVA8702',
                    'cod_cliente' => 'VA-8748',
                    'cod_arquivo_dados' => 'CEVA8703-Ventos do Araripe VA8748(CEVA8703)',
                    'parque_id' => 19 // Ventos de Santo Estevão III
                ],
                [
                    'cod_MSTK' => 'EDF7419',
                    'cod_cliente' => 'MM6074',
                    'cod_arquivo_dados' => 'EDF7419-AMA-VENTOSBAHIA_VIII(EDF-7419)',
                    'parque_id' => 20 // Ventos da Bahia VIII
                ],
                [
                    'cod_MSTK' => 'CHU5309',
                    'cod_cliente' => 'Chuí 09',
                    'cod_arquivo_dados' => 'CHU-5309 - TORRE CHUI-IX - AMA(CHU5309)',
                    'parque_id' => 21 // Chuí 09
                ],
                [
                    'cod_MSTK' => 'HRM5324',
                    'cod_cliente' => 'Verace 24',
                    'cod_arquivo_dados' => 'HRM-5324 - TORRE UEHM 24 - AMA(HRM5324)',
                    'parque_id' => 22 // Verace 24
                ],
                [
                    'cod_MSTK' => 'HRM5325',
                    'cod_cliente' => 'Verace 25',
                    'cod_arquivo_dados' => 'HRM-5325 - TORRE UEHM 25 - AMA(HRM5325)',
                    'parque_id' => 23 // Verace 25
                ],
                [
                    'cod_MSTK' => 'HRM5326',
                    'cod_cliente' => 'Verace 26',
                    'cod_arquivo_dados' => 'HRM-5326 - TORRE UEHM 26 - AMA(HRM5326)',
                    'parque_id' => 24 // Verace 26
                ],
                [
                    'cod_MSTK' => 'HRM5327',
                    'cod_cliente' => 'Verace 27',
                    'cod_arquivo_dados' => 'HRM-5327 - TORRE UEHM 27 - AMA(HRM5327)',
                    'parque_id' => 25 // Verace 27
                ],
                [
                    'cod_MSTK' => 'HRM5328',
                    'cod_cliente' => 'Verace 28',
                    'cod_arquivo_dados' => 'HRM-5328 - TORRE UEHM 28 - AMA(HRM5328)',
                    'parque_id' => 26 // Verace 28
                ],
                [
                    'cod_MSTK' => 'HRM5329',
                    'cod_cliente' => 'Verace 29',
                    'cod_arquivo_dados' => 'HRM-5329 - TORRE UEHM 29 - AMA(HRM5329)',
                    'parque_id' => 27 // Verace 29
                ],
                [
                    'cod_MSTK' => 'HRM5330',
                    'cod_cliente' => 'Verace 30',
                    'cod_arquivo_dados' => 'HRM-5330 - TORRE UEHM 30 - AMA(HRM5330)',
                    'parque_id' => 28 // Verace 30
                ],
                [
                    'cod_MSTK' => 'HRM5331',
                    'cod_cliente' => 'Verace 31',
                    'cod_arquivo_dados' => 'HRM-5331 - TORRE UEHM 31 - AMA(HRM5331)',
                    'parque_id' => 29 // Verace 31
                ],
                [
                    'cod_MSTK' => 'HRM5334',
                    'cod_cliente' => 'Verace 34',
                    'cod_arquivo_dados' => 'HRM-5334 - TORRE UEHM 34 - AMA(HRM5334)',
                    'parque_id' => 30 // Verace 34
                ],
                [
                    'cod_MSTK' => 'HRM5335',
                    'cod_cliente' => 'Verace 35',
                    'cod_arquivo_dados' => 'HRM-5335 - TORRE UEHM 35 - AMA(HRM5335)',
                    'parque_id' => 31 // Verace 35
                ],
                [
                    'cod_MSTK' => 'HRM5336',
                    'cod_cliente' => 'Verace 36',
                    'cod_arquivo_dados' => 'HRM-5336 - TORRE UEHM 36 - AMA(HRM5336)',
                    'parque_id' => 32 // Verace 36
                ]
            )
        );

    }

    public function down()
    {
        Schema::dropIfExists('torres');
    }
}
