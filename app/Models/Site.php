<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $connection = 'mysql';

    protected $table = 'SITE';

    protected $primaryKey = 'codigo';

    protected $fillable = ['codigo', 'clicodigo', 'dessite', 'latsite', 'lngsite', 'infsite', 'tznsite', 'sitename','estacao', 'hrsenvio', 'ultenvio', 'emlenvio01', 'emlenvio02', 'emlenvio03'];

    public function sites() {
        return $this->belongsTo('Messtechnik\Models\Cliente', 'clicodigo', 'codigo');
    }
}
