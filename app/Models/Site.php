<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'site';

    protected $fillable = [
        'sitename',
        'dessite',
        'latsite',
        'lngsite',
        'infsite',
        'tznsite',
        'estacao',
        'hrsenvio',
        'ultenvio',
        'emlenvio01',
        'emlenvio02',
        'emlenvio03',
        'clicodigo'
    ];

    #public function torres() {
    #    return $this->hasMany('Messtechnik\Models\Tower', 'parque_id');
    #}

    public function cliente() {
        return $this->belongsTo('Messtechnik\Models\Client','clicodigo', 'codigo');
    }

    public function sensores() {
        return $this->hasMany('Messtechnik\Models\Sensor', 'torre_id');
    }

    public function parque() {
        return $this->belongsTo('Messtechnik\Models\Site','parque_id', 'id');
    }

}
