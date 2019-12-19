<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'cliente';

    protected $fillable = [
        'codigo',
        'razaosocial',
        'endereco'
    ];

    public function users() {
        return $this->hasMany('Messtechnik\Models\User', 'cliente_codigo', 'codigo');
    }

    public function torres() {
        return $this->hasMany('Messtechnik\Models\Site', 'empresa_id');
    }
}
