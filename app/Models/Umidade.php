<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Umidade extends Model
{
    protected $table = 'umidades';

    protected $fillable = [
        'nome', 'leitura', 'sensor_id', 'created_at'
    ];

    public function sensor() {
        return $this->belongsTo('App\Models\Sensor', 'sensor_id', 'id');
    }
}