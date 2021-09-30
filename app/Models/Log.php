<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'usuario', 'diretorio', 'status', 'mensagem'
    ];

}
