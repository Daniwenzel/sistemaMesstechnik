<?php

namespace App\Console\Commands;

use App\Models\Sensor;
use App\Models\Tower;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class LerArquivo extends Command
{

    protected $signature = 'file:read {stream} {torreId}';

    protected $description = 'Ler arquivo da torre';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Recebe os parametros do arquivo de dados dentro do servidor SMB
        // (enviados pelo comando BuscarDadosServidor)
        $stream = $this->argument('stream');
        $torreId = $this->argument('torreId');


        // Leitura do arquivo e conversão para uma tabela de linhas e colunas
        // (semelhante ao utilizado na análise do LoggerNet)
        $dados = '';
        while (!feof($stream)) {
            $dados .= fread($stream, 8192);
        }
        $linhas = explode(PHP_EOL, $dados);
        foreach ($linhas as $line) {
            $array[] = explode(",", $line);
        }

        // Inicia uma lista vazia para armazenar as leituras dos sensores
        $barometros = [];
        $anemometros = [];
        $windvanes = [];
        $temperaturas = [];
        $umidades = [];
        $baterias = [];
        $precipitacoes = [];
        $anemometrosVerticais = [];

        // Percorre pela tabela iniciando na linha 3 (ignora as informações referentes a torre, tipo do sensor...) e
        // na coluna 2 (ignora o timestamp e o numero do registro)
        for ($row = 3; $row < count($array); $row++) {
            for ($col = 2; $col < count($array[$row]); $col++) {

                // Pega o nome dos sensores na linha 2 do arquivo e remove os seus sufixos
                // de estatistica (avg, min, max, std)
                $nomeEstatistico = str_ireplace(array("\r", "\n", " ", '"'), '', trim($array[0][$col], '"'));
                $nomeSensor = str_ireplace(array("Avg", "Min", "Max", "Std"), '', $nomeEstatistico);

                $array[$row][$col] = str_ireplace(array('"', ' '), null, $array[$row][$col]);
//                $vrau = is_float($array[$row][$col]);

//                var_dump($vrau);
//
//                $array[$row][$col] = is_float($array[$row][$col]) ? null : $array[$row][$col];

                // Se o nome do sensor iniciar por B_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de barometros.
                if (substr($nomeSensor, 0, 2) === 'B_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $barometros[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }

                // Se o nome do sensor iniciar por AN_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de anemometros.
                elseif (substr($nomeSensor, 0, 3) === 'AN_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $anemometros[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }

                // Se o nome do sensor iniciar por WV_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de windvanes.
                elseif (substr($nomeSensor, 0, 3) === 'WV_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $windvanes[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }

                // Se o nome do sensor iniciar por T_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de temperaturas.
                elseif (substr($nomeSensor, 0, 2) === 'T_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $temperaturas[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }

                // Se o nome do sensor iniciar por U_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de umidades.
                elseif (substr($nomeSensor, 0, 2) === 'U_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $umidades[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }

                // Se o nome do sensor iniciar por Bat12V_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de baterias.
                elseif ((substr($nomeSensor, 0, 7) === 'Bat12V_') ||
                    (substr($nomeSensor, 0, 17) === 'Logger_Voltage_R_')) {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $baterias[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => str_replace(array("\r", "\n", " "), '', $array[$row][$col]),
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }

                // Se o nome do sensor iniciar por SC_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de precipitacoes.
                elseif (substr($nomeSensor, 0, 3) === 'SC_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $precipitacoes[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }

                // Se o nome do sensor iniciar por ANV_, procura pelo registro do sensor na tabela Sensors,
                // e cria um novo registro caso ainda não exista. Salva a leitura na lista de anemometrosVerticais.
                elseif (substr($nomeSensor, 0, 4) === 'ANV_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => $nomeSensor,
                        'torre_id' => $torreId
                    ]);
                    $anemometrosVerticais[] = [
                        'nome' => $nomeEstatistico,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"'),
                        'updated_at' => now()
                    ];
                }
                else {
                    // Caso o nome do sensor não iniciar por nenhum dos prefixos, retorna mensagem de erro no terminal
                    echo 'Não foi possível encontrar um sensor cadastrado com este nome';
                }
            }
        }

        // Salva o conteúdo das listas de sensores nas respectivas tabelas dentro do banco de dados
//        DB::table('anemometros')->insert($anemometros);
//        DB::table('windvanes')->insert($windvanes);
//        DB::table('barometros')->insert($barometros);
//        DB::table('temperaturas')->insert($temperaturas);
//        DB::table('umidades')->insert($umidades);
//        DB::table('baterias')->insert($baterias);
//        DB::table('precipitacoes')->insert($precipitacoes);
//        DB::table('anemometro_verticais')->insert($anemometrosVerticais);
    }
}