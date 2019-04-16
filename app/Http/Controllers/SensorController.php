<?php

namespace App\Http\Controllers;

use App\Models\Anemometro;
use App\Models\Barometro;
use App\Models\Bateria;
use App\Models\Sensor;
use App\Models\Temperatura;
use App\Models\Umidade;
use App\Models\Windvane;
use Illuminate\Support\Facades\DB;

class SensorController extends Controller
{
    public $array = [];

    public function openFile() {
        $arq = file(storage_path('app/testetxt')); // array de strings

        foreach ($arq as $line) {
            $array[] = explode(",", $line); // array de array
        }

        $barometros = [];
        $anemometros = [];
        $windvanes = [];
        $temperaturas = [];
        $umidades = [];
        $baterias = [];

        for ($row=4; $row<count($array);$row++) {  // inicia na linha 4 (ignora as informações referentes a torre, tipo das leituras)
            for ($col=2; $col<count($array[$row]); $col++) { // inicia na coluna 2 (ignora o timestamp e o numero do registro)

                $nome = trim($array[1][$col], '"');

                if (substr($nome, 0, 2) === 'B_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => 'Barometro-teste',
                        'torre_id' => 5
                    ]);

                    array_push($barometros, [
                        'nome' => $nome,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"')
                    ]);
                }

                elseif (substr($nome, 0, 3) === 'AN_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => 'Anemometro-teste',
                        'torre_id' => 5
                    ]);

                    array_push($anemometros, [
                        'nome' => $nome,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"')
                    ]);
                }

                elseif (substr($nome, 0, 3) === 'WV_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => 'Windvane-teste',
                        'torre_id' => 5
                    ]);

                    array_push($windvanes, [
                        'nome' => $nome,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"')
                    ]);
                }

                elseif (substr($nome, 0, 2) === 'T_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => 'Temperatura-teste',
                        'torre_id' => 5
                    ]);

                    array_push($temperaturas, [
                        'nome' => $nome,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"')
                    ]);
                }

                elseif (substr($nome, 0, 2) === 'U_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => 'Umidade-teste',
                        'torre_id' => 5
                    ]);

                    array_push($umidades, [
                        'nome' => $nome,
                        'leitura' => $array[$row][$col],
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"')
                    ]);
                }

                elseif (substr($nome, 0, 7) === 'Bat12V_') {
                    $sensor = Sensor::firstOrCreate([
                        'nome' => 'Bateria-teste',
                        'torre_id' => 5
                    ]);

                    array_push($baterias, [
                        'nome' => str_replace('"','',$nome),
                        'leitura' => str_replace(array("\r", "\n", " "), '', $array[$row][$col]),
                        'sensor_id' => $sensor->id,
                        'created_at' => trim($array[$row][0], '"')
                    ]);

                }
            }
        }

        DB::table('anemometros')->insert($anemometros);
        DB::table('windvanes')->insert($windvanes);
        DB::table('barometros')->insert($barometros);
        DB::table('temperaturas')->insert($temperaturas);
        DB::table('umidades')->insert($umidades);
        DB::table('baterias')->insert($baterias);

    }
}
