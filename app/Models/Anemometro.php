<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anemometro extends Model
{
    protected $table = 'anemometros';

    protected $fillable = [
        'nome', 'leitura', 'sensor_id', 'created_at', 'updated_at'
    ];

    public function sensor() {
        return $this->belongsTo('App\Models\Sensor', 'sensor_id', 'id');
    }
}
