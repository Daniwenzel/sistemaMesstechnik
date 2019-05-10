<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    protected $table = 'torres';

    public function sensores() {
        return $this->hasMany('App\Models\Sensor', 'torre_id');
    }

    public function parque() {
        return $this->belongsTo('App\Models\WindFarm','parque_id', 'id');
    }
}
