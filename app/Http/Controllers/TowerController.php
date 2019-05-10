<?php

namespace App\Http\Controllers;

use App\Models\Anemometro;
use App\Models\Barometro;
use App\Models\Bateria;
use App\Models\Sensor;
use App\Models\Temperatura;
use App\Models\Tower;
use App\Models\Umidade;
use App\Models\Windvane;

class TowerController extends Controller
{
    public function showTowerInfo($tower_id) {
        /* Buscando pelos sensores da torre
           Através do torre_id, mas torre_id não
           Consta no arquivo txt lido */

        $torre = Tower::find($tower_id)->first();

        $sensores = Sensor::where('torre_id', $tower_id)->get();

        foreach ($sensores as $sensor) {

            if ($sensor->barometro->first()) {
                $leituras[$sensor->nome] = $sensor->barometro->where('created_at', Barometro::latest()->value('created_at'))
                    ->where('sensor_id', $sensor->id);
                $leituras[$sensor->nome]->push(['marca' => 'images/sensors/'.$sensor->marca]);
            }

            elseif($sensor->anemometro->first()) {
                $leituras[$sensor->nome] = $sensor->anemometro->where('created_at', Anemometro::latest()->value('created_at'))
                    ->where('sensor_id', $sensor->id);
                $leituras[$sensor->nome]->push(['marca' => 'images/sensors/'.$sensor->marca]);
            }

            elseif($sensor->windvane->first()) {
                $leituras[$sensor->nome] = $sensor->windvane->where('created_at', Windvane::latest()->value('created_at'))
                    ->where('sensor_id', $sensor->id);
                $leituras[$sensor->nome]->push(['marca' => 'images/sensors/'.$sensor->marca]);
            }

            elseif($sensor->temperatura->first()) {
                $leituras[$sensor->nome] = $sensor->temperatura->where('created_at', Temperatura::latest()->value('created_at'))
                    ->where('sensor_id', $sensor->id);
                $leituras[$sensor->nome]->push(['marca' => 'images/sensors/'.$sensor->marca]);
            }

            elseif($sensor->umidade->first()) {
                $leituras[$sensor->nome] = $sensor->umidade->where('created_at', Umidade::latest()->value('created_at'))
                    ->where('sensor_id', $sensor->id);
                $leituras[$sensor->nome]->push(['marca' => 'images/sensors/'.$sensor->marca]);
            }

            elseif($sensor->bateria->first()) {
                $leituras[$sensor->nome] = $sensor->bateria->where('created_at', Bateria::latest()->value('created_at'))
                    ->where('sensor_id', $sensor->id);
                $leituras[$sensor->nome]->push(['marca' => 'images/sensors/'.$sensor->marca]);
           }

        }

        $created_at = array_values($leituras)[0]->first()->created_at;

        return view('towerinfo', compact(['leituras', 'torre', 'created_at']));
    }
}
