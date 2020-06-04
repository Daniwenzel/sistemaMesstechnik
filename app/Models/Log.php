<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $connection = 'mysql';

    protected $table = 'logs';

    protected $fillable = [
        'diretorio', 'status', 'mensagem'
    ];

}
