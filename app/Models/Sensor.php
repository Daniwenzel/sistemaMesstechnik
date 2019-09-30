<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensors';

    protected $fillable = [
        'nome', 'leitura', 'tipo', 'torre_id', 'created_at'
    ];

    public function torre() {
        return $this->belongsTo('Messtechnik\Models\Tower', 'torre_id', 'id');
    }

    public function anemometro() {
        return $this->hasMany('Messtechnik\Models\Anemometro', 'sensor_id', 'id');
    }

    public function barometro() {
        return $this->hasMany('Messtechnik\Models\Barometro', 'sensor_id', 'id');
    }

    public function windvane() {
        return $this->hasMany('Messtechnik\Models\Windvane', 'sensor_id', 'id');
    }

    public function temperatura() {
        return $this->hasMany('Messtechnik\Models\Temperatura', 'sensor_id', 'id');
    }

    public function umidade() {
        return $this->hasMany('Messtechnik\Models\Umidade', 'sensor_id', 'id');
    }

    public function bateria() {
        return $this->hasMany('Messtechnik\Models\Bateria', 'sensor_id', 'id');
    }

    public function anemometroVertical() {
        return $this->hasMany('Messtechnik\Models\Umidade', 'sensor_id', 'id');
    }

    public function precipitacao() {
        return $this->hasMany('Messtechnik\Models\Bateria', 'sensor_id', 'id');
    }
}
