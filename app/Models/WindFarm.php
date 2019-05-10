<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindFarm extends Model
{
    protected $table = 'parque_eolicos';

    public function torres() {
        return $this->hasMany('App\Models\Tower', 'parque_id');
    }

    public function empresa() {
        return $this->belongsTo('App\Models\Company','empresa_id', 'id');
    }
}
