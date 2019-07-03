<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnemometroVertical extends Model
{
    protected $table = 'anemometro_verticais';

    protected $fillable = [
        'nome', 'leitura', 'sensor_id', 'created_at', 'updated_at'
    ];

    public function sensor() {
        return $this->belongsTo('App\Models\Sensor', 'sensor_id', 'id');
    }
}
