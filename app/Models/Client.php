<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'cliente';

    protected $fillable = [
        'codigo', 'razaosocial', 'endereco'
    ];

    public function users() {
        return $this->hasMany('Messtechnik\User', 'cliente_codigo', 'codigo');
    }

    public function parques() {
        return $this->hasMany('Messtechnik\Models\WindFarm', 'empresa_id');
    }
}
