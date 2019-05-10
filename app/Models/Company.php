<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'nome', 'cnpj', 'telefone', 'email',
    ];

    public function users() {
        return $this->hasMany('App\User', 'empresa_id', 'id');
    }

    public function parques() {
        return $this->hasMany('App\Models\WindFarm', 'empresa_id');
    }
}
