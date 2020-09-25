<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $connection = 'mysql';

    // Nome deve ser maiusculo, pois firebird utiliza o case nos caracteres
    protected $table = 'SITE';

    protected $primaryKey = 'codigo';

    protected $fillable = ['codigo', 'clicodigo', 'dessite', 'latsite', 'lngsite', 'infsite', 'tznsite', 'sitename','estacao', 'hrsenvio', 'ultenvio', 'emlenvio01', 'emlenvio02', 'emlenvio03'];

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
