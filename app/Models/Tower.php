<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    protected $table = 'torres';

    protected $fillable = [
        'parque_id', 'cod_MSTK', 'cod_cliente'
    ];

    public function sensores() {
        return $this->hasMany('Messtechnik\Models\Sensor', 'torre_id');
    }

    public function parque() {
        return $this->belongsTo('Messtechnik\Models\WindFarm','parque_id', 'id');
    }
}
