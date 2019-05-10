<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensors';

    protected $fillable = [
        'nome', 'leitura', 'torre_id', 'created_at'
    ];

    public function torre() {
        return $this->belongsTo('App\Models\Tower', 'torre_id', 'id');
    }

    public function anemometro() {
        return $this->hasMany('App\Models\Anemometro', 'sensor_id', 'id');
    }

    public function barometro() {
        return $this->hasMany('App\Models\Barometro', 'sensor_id', 'id');
    }

    public function windvane() {
        return $this->hasMany('App\Models\Windvane', 'sensor_id', 'id');
    }

    public function temperatura() {
        return $this->hasMany('App\Models\Temperatura', 'sensor_id', 'id');
    }

    public function umidade() {
        return $this->hasMany('App\Models\Umidade', 'sensor_id', 'id');
    }

    public function bateria() {
        return $this->hasMany('App\Models\Bateria', 'sensor_id', 'id');
    }
}
