<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    protected $table = 'torres';

    public function sensores() {
        return $this->hasMany('App\Models\Sensor', 'torre_id');
    }
}
