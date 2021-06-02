<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    protected $connection = 'pgsql';

	protected $table = 'arquivos';

	protected $fillable = ['path', 'sitcodigo', 'tipo'];

	public function site() {
		return $this->belongsTo('Messtechnik\Models\Site', 'sitcodigo', 'codigo');
	}
}
