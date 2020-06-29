<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemSite extends Model
{
    protected $connection = 'mysql';

	protected $table = 'imagens_site';

	protected $fillable = ['path', 'sitcodigo'];

	public function site() {
		return $this->belongsTo('Messtechnik\Models\Site', 'sitcodigo', 'codigo');
	}
}
