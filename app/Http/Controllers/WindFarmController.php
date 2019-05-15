<?php

namespace App\Http\Controllers;

use App\Models\Tower;
use App\Models\WindFarm;
use Illuminate\Support\Facades\Auth;

class WindFarmController extends Controller
{
    public function showWindfarmList() {
        if(Auth::user()->hasRole('Admin')) {
            $windfarms = WindFarm::orderBy('nome', 'asc')
                ->paginate(5);
        }
        else {
            $windfarms = WindFarm::where('empresa_id', Auth::user()->empresa_id)
                ->orderBy('nome', 'asc')
                ->paginate(2);

        }

        return view('windfarm', compact('windfarms'));
    }

    public function showWindfarm($farm_id) {

        if(WindFarm::find($farm_id)->empresa_id !== Auth::user()->empresa_id) {
            if(!Auth::user()->hasRole('Admin')) {
                return view('errors/unallowed');
            }
        }

        $torres = Tower::where('parque_id', $farm_id)
            ->orderBy('cod_MSTK', 'asc')
            ->paginate(3);

        return view('windfarminfo', compact('torres'));
    }
}
