<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'clientes';

    protected $primaryKey = 'codigo';

    protected $fillable = ['razaosocial', 'endereco'];

    public function users() {
        return $this->hasMany('Messtechnik\Models\User', 'clicodigo', 'codigo');
    }

   public function torres() {
       return $this->hasMany('Messtechnik\Models\Site', 'clicodigo', 'codigo');
   }
}
