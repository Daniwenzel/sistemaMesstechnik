<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Oem extends Model
{
    protected $table = 'oem';

    protected $primaryKey = 'codigo';

    protected $fillable = ['sitcodigo', 'nome_mstk', 'cliente', 'status', 'contrato_vencimento', 'cidade', 'lat', 'long', 'lat_decimal','long_decimal', 'clicodigo'];

    public function cliente() {
        return $this->belongsTo('Messtechnik\Models\Cliente', 'clicodigo', 'codigo');
    }

    public function imagem() {
        return $this->hasMany('Messtechnik\Models\ImagemSite', 'oemcodigo', 'codigo');
    }

    public function pendencias() {
        return $this->hasMany('Messtechnik\Models\Pendencia', 'oemcodigo', 'codigo');
    }

    public function atendimentos() {
        return $this->hasMany('Messtechnik\Models\Atendimento', 'oemcodigo', 'codigo');
    }

    public function equipamentos() {
        return $this->hasMany('Messtechnik\Models\Equipamento', 'oemcodigo', 'codigo');
    }
}