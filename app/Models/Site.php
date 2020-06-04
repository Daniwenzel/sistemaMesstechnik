<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    protected $connection = 'firebird';

    protected $table = 'SITE';

    protected $fillable = [
        'CODIGO', 'CLICODIGO', 'DESSITE', 'LATSITE', 'LNGSITE', 'INFSITE', 'TZNSITE', 'SITENAME',
        'ESTACAO', 'HRSENVIO', 'ULTENVIO', 'EMLENVIO01', 'EMLENVIO02', 'EMLENVIO03'
        ];

    public function sites() {
        return $this->belongsTo('Messtechnik\Models\Cliente', 'CLICODIGO', 'CODIGO');
    }
}
