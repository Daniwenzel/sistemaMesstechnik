<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temperatura extends Model
{
    protected $table = 'temperaturas';

    protected $fillable = [
        'nome', 'leitura', 'sensor_id', 'created_at'
    ];

    public function sensor() {
        return $this->belongsTo('App\Models\Sensor', 'sensor_id', 'id');
    }
}