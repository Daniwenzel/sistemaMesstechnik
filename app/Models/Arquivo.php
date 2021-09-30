<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    protected $connection = 'mysql';

	protected $table = 'arquivos';

	protected $primaryKey = 'codigo';

	protected $fillable = ['path', 'oemcodigo', 'tipo'];

	public function site() {
		return $this->belongsTo('Messtechnik\Models\Oem', 'oemcodigo', 'codigo');
	}
}
