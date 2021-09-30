<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Pendencia extends Model
{
    protected $connection = 'mysql';

    protected $table = 'pendencias';

    protected $primaryKey = 'codigo';

    protected $appends = ['alerta'];

    protected $fillable = ['sitcodigo', 'equipcodigo', 'descricao', 'gravidade', 'status', 'arquivo'];

    public function torre() {
        return $this->belongsTo('Messtechnik\Models\Oem', 'codigo', 'oemcodigo');
    }

    public function getAlertaAttribute() {
        if($this->status == 'Realizado') {
            return 'alert-success';
        }
        else {
            switch($this->gravidade) {
                case 'Normal': return 'alert-warning';
                case 'Urgente': return 'alert-danger';
                default: return 'alert-secondary';
            }
        }
    }
}