<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
	protected $table = 'arquivos';

	protected $primaryKey = 'codigo';

	protected $fillable = ['path', 'sitcodigo', 'tipo'];

	public function site() {
		return $this->belongsTo('Messtechnik\Models\Oem', 'oemcodigo', 'codigo');
	}
}
