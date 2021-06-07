<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'equipamentos';

    protected $primaryKey = 'codigo';

    protected $fillable = ['descricao', 'numero_serie', 'data_instalacao', 'tempo_operacao', 'data_substituicao', 'estado', 'oemcodigo'];

    public function oem() {
        return $this->belongsTo('Messtechnik\Models\Oem', 'codigo', 'oemcodigo')
    }
}
