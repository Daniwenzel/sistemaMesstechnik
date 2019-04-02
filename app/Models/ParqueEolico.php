<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParqueEolico extends Model
{
    protected $table = 'parque_eolicos';

    public function torres() {
        return $this->hasMany('App/Models/Torre', 'parque_id');
    }
}
