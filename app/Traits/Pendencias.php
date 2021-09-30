<?php

namespace Messtechnik\Traits;

use Illuminate\Support\Facades\DB;
use Messtechnik\Models\Pendencia;
use Messtechnik\Traits\Logs;

trait Pendencias
{
    use Logs;

	private function atualizarPendencia($equipcodigo, $oemcodigo, $descricao, $gravidade, $status) {
        try {
            Pendencia::updateOrCreate(
                ['equipcodigo' => $equipcodigo],
                ['oemcodigo' => $oemcodigo,
                'descricao' => $descricao,
                'gravidade' => $gravidade,
                'status' => $status]
            );
        } catch(\Exception $e) {
			$this->createLog('-', 'error', 'Falha ao tentar atualizar pendência do equipamento com código: '. $equipcodigo);
			abort(404);
        }
	}

    private function adicionarPendencia($oemcodigo, $equipcodigo, $descricao, $gravidade, $status) {
        return Pendencia::create([
            'oemcodigo' => $oemcodigo,
            'equipcodigo' => $equipcodigo,
            'descricao' => $descricao,
            'gravidade' => $gravidade,
            'status' => $status
        ]);
    }
}