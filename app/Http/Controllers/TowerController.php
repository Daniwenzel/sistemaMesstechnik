<?php

namespace Messtechnik\Http\Controllers;

use Messtechnik\AnemometroVertical;
use Messtechnik\Models\Anemometro;
use Messtechnik\Models\Barometro;
use Messtechnik\Models\Bateria;
use Messtechnik\Models\Precipitacao;
use Messtechnik\Models\Temperatura;
use Messtechnik\Models\Tower;
use Messtechnik\Models\Umidade;
use Messtechnik\Models\WindFarm;
use Messtechnik\Models\Windvane;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TowerController extends Controller
{
    public function show($tower_id) {
        // Busca pelo registro da torre selecionada e seus sensores
        $torre = Tower::find($tower_id);
        $sensores = $torre->sensores;

        // Inicia uma lista vazia para armazenar as leituras de cada sensor
        $barometros = [];
        $anemometros = [];
        $windvanes = [];
        $temperaturas = [];
        $umidades = [];
        $baterias = [];
        $precipitacoes = [];
        $anemometrosVerticais = [];


        if (count($sensores) !== 0) {
            foreach ($sensores as $sensor) {
                switch($sensor->tipo) {
                    case 'Barometro':
                        $barometros[$sensor->nome] = Barometro::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                    case 'Anemometro':
                        $anemometros[$sensor->nome] = Anemometro::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                    case 'Windvane':
                        $windvanes[$sensor->nome] = Windvane::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                    case 'Temperatura':
                        $temperaturas[$sensor->nome] = Temperatura::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                    case 'Umidade':
                        $umidades[$sensor->nome] = Umidade::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                    case 'Bateria':
                        $baterias[$sensor->nome] = Bateria::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                    case 'Precipitacao':
                        $precipitacoes[$sensor->nome] = Precipitacao::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                    case 'AnemometroVertical':
                        $anemometrosVerticais[$sensor->nome] = AnemometroVertical::whereDate('updated_at', $torre->updated_at)
                            ->where('sensor_id', $sensor->id)
                            ->where('nome', 'like','%'.'Avg')
                            ->pluck('leitura')
                            ->toArray();
                        break;
                }
            }
        }

        else {
            Session::flash('message', 'Não foi possível encontrar registros de sensores para essa torre.');
        }

        return view('towerinfo', compact([
            'torre',
            'barometros',
            'anemometros',
            'windvanes',
            'temperaturas',
            'umidades',
            'baterias',
            'precipitacoes',
            'anemometrosVerticais'
        ]));
    }

    /**
     * Mostra a view para cadastrar uma torre
     *
     * @param $farm_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($farm_id) {
        $parque = WindFarm::find($farm_id);

        return view('towerregister', compact('parque'));
    }

    /**
     * Cadastra uma nova torre
     *
     * @param Request $request
     * @param $farm_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $farm_id) {
        Tower::create([
            'cod_MSTK' => $request['cod_mstk'],
            'cod_cliente' => $request['cod_cliente'],
            'parque_id' => $farm_id,
            'cod_arquivo_dados' => $request['cod_arquivo_dados']
        ])->save();

        Session::flash('message', 'Torre cadastrada com sucesso!');
        return redirect()->back();
    }
}
