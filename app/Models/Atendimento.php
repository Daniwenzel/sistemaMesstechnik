<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    protected $table = 'atendimentos';

    protected $primaryKey = 'codigo';

    protected $fillable = ['sitcodigo', 'descricao', 'tipo', 'dataInicio', 'dataFim'];

    public function torre() {
        return $this->belongsTo('Messtechnik\Models\Site', 'codigo', 'sitcodigo');
    }
}