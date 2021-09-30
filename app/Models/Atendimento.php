<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;
use Messtechnik\Models\Arquivo;

class Atendimento extends Model
{
    protected $connection = 'mysql';

    protected $table = 'atendimentos';

    protected $primaryKey = 'codigo';

    protected $fillable = ['oemcodigo', 'descricao', 'tipo', 'arquivo', 'dataInicio', 'dataFim'];

    public function torre() {
        return $this->belongsTo('Messtechnik\Models\Oem', 'oemcodigo', 'codigo');
    }
}