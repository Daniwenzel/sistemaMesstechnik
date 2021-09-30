<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'oem';

    protected $primaryKey = 'codigo';

    protected $fillable = ['sitcodigo', 'nome_mstk', 'cliente', 'status', 'contrato_vencimento', 'cidade', 'lat', 'long', 'lat_decimal','long_decimal', 'clicodigo'];

    public function cliente() {
        return $this->belongsTo('Messtechnik\Models\Cliente', 'clicodigo', 'codigo');
    }

    public function imagem() {
        return $this->hasMany('Messtechnik\Models\ImagemSite', 'sitcodigo', 'codigo');
    }

    public function pendencias() {
        return $this->hasMany('Messtechnik\Models\Pendencia', 'sitcodigo', 'codigo');
    }

    public function atendimentos() {
        return $this->hasMany('Messtechnik\Models\Atendimento', 'sitcodigo', 'codigo');
    }
}