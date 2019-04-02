<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';

    public function users() {
        return $this->hasMany('App/Models/User', 'empresa_id');
    }

    public function parques() {
        return $this->hasMany('App/Models/ParqueEolico', 'empresa_id');
    }
}
