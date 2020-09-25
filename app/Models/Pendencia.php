<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Pendencia extends Model
{
    protected $connection = 'mysql';

    protected $table = 'pendencias';

    protected $primaryKey = 'codigo';

    protected $appends = ['alerta'];

    protected $fillable = ['sitcodigo', 'descricao', 'gravidade', 'status'];

    public function torre() {
        return $this->belongsTo('Messtechnik\Models\Site', 'codigo', 'sitcodigo');
    }

    public function getAlertaAttribute() {
        if($this->status == 'Realizado') {
            return 'alert-primary';
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
