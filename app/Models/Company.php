<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'firebird';

    protected $table = 'CLIENTE';

    protected $fillable = [
        'CODIGO', 'RAZAOSOCIAL', 'ENDERECO'
    ];

    public function users() {
        return $this->hasMany('Messtechnik\User', 'clicodigo', 'CODIGO');
    }

   public function torres() {
       return $this->hasMany('Messtechnik\Models\Site', 'CLICODIGO', 'CODIGO');
   }
}
