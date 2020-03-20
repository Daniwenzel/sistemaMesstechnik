<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'CLIENTE';

    protected $fillable = [
        'CODIGO', 'RAZAOSOCIAL', 'ENDERECO'
    ];

    public function users() {
        return $this->hasMany('Messtechnik\User', 'cliente_codigo', 'CODIGO');
    }

//    public function parques() {
//        return $this->hasMany('Messtechnik\Models\WindFarm', 'empresa_id');
//    }
}
