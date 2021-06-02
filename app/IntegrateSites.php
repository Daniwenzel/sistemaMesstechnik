<?php

namespace Messtechnik;

use Illuminate\Support\Facades\DB;
use Messtechnik\Models\Site;

class IntegrateSites
{
	public function integrarSites()
	{
		$sites = Site::on('firebird')->get();

		foreach ($sites as $site) {
			Site::updateOrCreate(
				['codigo' => $site->CODIGO],
				['clicodigo' => $site->CLICODIGO,
				'dessite' => $site->DESSITE,
				// Não atualizar latitude e longitude, pois estes valores não estão sendo salvos no database firebird (retornam NULL)
				//'latsite' => $site->LATSITE,
				//'lngsite' => $site->LNGSITE,
				'infsite' => $site->INFSITE,
				'tznsite' => $site->TZNSITE,
				'sitename' => $site->SITENAME,
				'estacao' => $site->ESTACAO,
				'hrsenvio' => $site->HRSENVIO,
				'ultenvio' => $site->ULTENVIO,
				'emlenvio01' => $site->EMLENVIO01,
				'emlenvio02' => $site->EMLENVIO02,
				'emlenvio03' => $site->EMLENVIO03]);
		}
	}
}