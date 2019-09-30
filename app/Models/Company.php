<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'nome', 'cnpj', 'telefone', 'email',
    ];

    public function users() {
        return $this->hasMany('Messtechnik\User', 'empresa_id', 'id');
    }

    public function parques() {
        return $this->hasMany('Messtechnik\Models\WindFarm', 'empresa_id');
    }
}
