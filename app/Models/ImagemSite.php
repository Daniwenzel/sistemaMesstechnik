<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemSite extends Model
{
	protected $table = 'imagens_site';

	protected $primaryKey = 'codigo';

	protected $fillable = ['path', 'sitcodigo'];

	public function site() {
		return $this->belongsTo('Messtechnik\Models\Oem', 'sitcodigo', 'codigo');
	}
}
