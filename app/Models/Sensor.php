<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'cfgfld';

    protected $fillable = [
        'vrscodigo',
        'fldname',
        'flddesc'
    ];

    public function site() {
        return $this->belongsTo('Messtechnik\Models\Site', 'torre_id', 'id');
    }
}
