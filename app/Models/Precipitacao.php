<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Precipitacao extends Model
{
    protected $table = 'precipitacoes';

    protected $fillable = [
        'nome', 'leitura', 'sensor_id', 'created_at', 'updated_at'
    ];

    public function sensor() {
        return $this->belongsTo('Messtechnik\Models\Sensor', 'sensor_id', 'id');
    }
}