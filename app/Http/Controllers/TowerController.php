<?php

namespace App\Http\Controllers;

use App\Models\Anemometro;
use App\Models\Barometro;
use App\Models\Bateria;
use App\Models\Temperatura;
use App\Models\Tower;
use App\Models\Umidade;
use App\Models\WindFarm;
use App\Models\Windvane;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TowerController extends Controller
{

    // Mostrar informações da torre selecionada
    public function showTowerInfo($tower_id) {
        /* Buscando pelos sensores da torre
           Através do torre_id, mas torre_id não
           Consta no arquivo txt lido */

        $torre = Tower::find($tower_id);
        $sensores = $torre->sensores;

        //$yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        $yesterday = Anemometro::latest()->first();

        // Inicia uma lista vazia para armazenar as leituras de cada sensor da torre
        $barometros = [];
        $anemometros = [];
        $windvanes = [];
        $temperaturas = [];
        $umidades = [];
        $baterias = [];
        $precipitacoes = [];
        $anemometrosVerticais = [];

//        dd($sensores[0]);

//        $leituras[$sensor->nome]->push(['marca' => 'images/sensors/'.$sensor->marca]);*/

        if (count($sensores) !== 0) {
            foreach ($sensores as $sensor) {
                if ($sensor->barometro->first()) {
                    $barometros[$sensor->nome] = Barometro::whereDate('updated_at', $torre->updated_at)
                        ->where('sensor_id', $sensor->id)
                        ->where('nome', 'like','%'.'Avg')
                        ->pluck('leitura')
                        ->toArray();
                } elseif ($sensor->anemometro->first()) {
                    $anemometros[$sensor->nome] = Anemometro::whereDate('updated_at', $torre->updated_at)
                        ->where('sensor_id', $sensor->id)
                        ->where('nome', 'like','%'.'Avg')
                        ->pluck('leitura')
                        ->toArray();
                } elseif ($sensor->windvane->first()) {
                    $windvanes[$sensor->nome] = Windvane::whereDate('updated_at', $torre->updated_at)
                        ->where('sensor_id', $sensor->id)
                        ->where('nome', 'like','%'.'Avg')
                        ->pluck('leitura')
                        ->toArray();
                } elseif ($sensor->temperatura->first()) {
                    $temperaturas[$sensor->nome] = Temperatura::whereDate('updated_at', $torre->updated_at)
                        ->where('sensor_id', $sensor->id)
                        ->where('nome', 'like','%'.'Avg')
                        ->pluck('leitura')
                        ->toArray();
                } elseif ($sensor->umidade->first()) {
                    $umidades[$sensor->nome] = Umidade::whereDate('updated_at', $torre->updated_at)
                        ->where('sensor_id', $sensor->id)
                        ->where('nome', 'like','%'.'Avg')
                        ->pluck('leitura')
                        ->toArray();
                } elseif ($sensor->bateria->first()) {
                    $baterias[$sensor->nome] = Bateria::whereDate('updated_at', $torre->updated_at)
                        ->where('sensor_id', $sensor->id)
                        ->where('nome', 'like','%'.'Avg')
                        ->pluck('leitura')
                        ->toArray();
                }
            }
        }

        else {
            Session::flash('message', 'Não foi possível encontrar registros de sensores para essa torre.');
        }

        return view('towerinfo', compact([
            'torre',
            'yesterday',
            'barometros',
            'anemometros',
            'windvanes',
            'temperaturas',
            'umidades',
            'baterias'
        ]));
    }

    public function showTowerList(Request $request, $farm_id)
    {
        // Se o usuário tentar entrar nesse escopo através do URL digitando um id que não seja o do próprio parque
        // eólico e ele não seja um ADMIN, redireciona para a view 'sem permissão'
        if ((WindFarm::find($farm_id)->empresa_id !== Auth::user()->empresa_id) && (!(Auth::user()->hasRole('Admin')))) {
            return view('errors.500');
        } else {
            $torres = Tower::where('parque_id', $farm_id)
                ->where('cod_cliente', 'like', '%'.$request['search'].'%')
                ->orderBy('cod_MSTK', 'asc')
                ->paginate(3);
            // Caso a busca não encontre resultados ou o parque selecionado não possui torres cadastradas
            if ($torres->isEmpty()) {
                Session::flash('message', 'Não foi possível encontrar torres com este nome, ou o parque ainda não possui torres cadastradas.');
            }
            return view('towers', compact(['torres', 'farm_id']));
        }
    }

    public function showRegisterTower($farm_id) {
        $parque = WindFarm::find($farm_id);

        return view('towerregister', compact('parque'));
    }

    public function registerTower(Request $request, $farm_id) {
        $torre = new Tower();
        $torre->cod_MSTK = $request['cod_mstk'];
        $torre->cod_cliente = $request['cod_cliente'];
        $torre->parque_id = $farm_id;
        $torre->cod_arquivo_dados = $request['cod_arquivo_dados'];

        Session::flash('message', 'Torre cadastrada com sucesso!');

        $torre->save();

        return redirect()->back();
    }
}
