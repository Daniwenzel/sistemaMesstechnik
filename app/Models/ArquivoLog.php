<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class ArquivoLog extends Model
{
    protected $table = 'arquivo_logs';

    protected $fillable = [
        'nome', 'diretorio', 'mensagem', 'status'
    ];
}
