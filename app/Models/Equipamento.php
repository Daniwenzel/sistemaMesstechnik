<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $connection = 'mysql';

    protected $table = 'equipamentos';

    protected $primaryKey = 'codigo';

    protected $appends = ['alerta'];

    protected $fillable = ['descricao', 'numero_serie', 'data_instalacao', 'tempo_operacao', 'data_substituicao', 'estado', 'oemcodigo'];

    public function oem() {
        return $this->belongsTo('Messtechnik\Models\Oem', 'oemcodigo', 'codigo');
    }

    public function getAlertaAttribute() {
        switch($this->estado) {
            case 'OK': 
                if($this->data_substituicao && date_diff(date_create(date('Y-m-d')), date_create($this->data_substituicao))->format('%R%a') < 365) {
                    return 'alert-warning';
                } else {
                    return 'alert-primary';
                }
            case 'Irregular': return 'alert-danger';
            case 'Substituído': return 'alert-dark';
            default: return 'alert-secondary';
        }
    }
}