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

        // {{ $sensor->barometro->where('created_at', Barometro::where('sensor_id', $sensor->id)->latest()->value('created_at')) }}

        foreach ($sensores as $sensor) {

            $ultimoAN = Anemometro::where('sensor_id', $sensor->id)->latest()->value('created_at');
            $ultimoWV = Windvane::where('sensor_id', $sensor->id)->latest()->value('created_at');
            $ultimoBA = Barometro::where('sensor_id', $sensor->id)->latest()->value('created_at');
            $ultimoTMP = Temperatura::where('sensor_id', $sensor->id)->latest()->value('created_at');
            $ultimoUMI = Umidade::where('sensor_id', $sensor->id)->latest()->value('created_at');
            $ultimoBT = Bateria::where('sensor_id', $sensor->id)->latest()->value('created_at');

            Anemometro::where('sensor_id', $sensor->id)->where('created_at', $ultimoAN)->first() ? $leituras[$sensor->nome] = Anemometro::where('sensor_id', $sensor->id)->where('created_at', $ultimoAN)->get() : false;
            Windvane::where('sensor_id', $sensor->id)->where('created_at', $ultimoWV)->first() ? $leituras[$sensor->nome] = Windvane::where('sensor_id', $sensor->id)->where('created_at', $ultimoWV)->get() : false;
            Barometro::where('sensor_id', $sensor->id)->where('created_at', $ultimoBA)->first() ? $leituras[$sensor->nome] = Barometro::where('sensor_id', $sensor->id)->where('created_at', $ultimoBA)->get() : false;
            Temperatura::where('sensor_id', $sensor->id)->where('created_at', $ultimoTMP)->first() ? $leituras[$sensor->nome] = Temperatura::where('sensor_id', $sensor->id)->where('created_at', $ultimoTMP)->get() : false;
            Umidade::where('sensor_id', $sensor->id)->where('created_at', $ultimoUMI)->first() ? $leituras[$sensor->nome] = Umidade::where('sensor_id', $sensor->id)->where('created_at', $ultimoUMI)->get() : false;
            Bateria::where('sensor_id', $sensor->id)->where('created_at', $ultimoBT)->first() ? $leituras[$sensor->nome] = Bateria::where('sensor_id', $sensor->id)->where('created_at', $ultimoBT)->get() : false;
        }

        dd($leituras);

        $created_at = now();

        return view('towerinfo', compact(['sensores', 'torre', 'created_at']));
    }
}
