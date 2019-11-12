<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class WindFarm extends Model
{
    protected $table = 'parque_eolicos';

    protected $fillable = [
        'nome', 'cod_EPE', 'empresa_id'
    ];

    public function torres() {
        return $this->hasMany('Messtechnik\Models\Tower', 'parque_id');
    }

    public function empresa() {
        return $this->belongsTo('Messtechnik\Models\Client','empresa_id', 'id');
    }
}
