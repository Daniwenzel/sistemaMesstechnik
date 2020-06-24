<?php

namespace Messtechnik;

use Illuminate\Support\Facades\DB;
use Messtechnik\Models\Cliente;

class IntegrateClients
{
	public function integrarClientes()
	{
		$clientes = Cliente::on('firebird')->get();

		foreach ($clientes as $cliente) {
			Cliente::updateOrCreate(
				['codigo' => $cliente->CODIGO],
				['razaosocial' => $cliente->RAZAOSOCIAL,
				'endereco' => $cliente->ENDERECO]);
		}
	}
}