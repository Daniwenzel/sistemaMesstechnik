<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemSite extends Model
{
    protected $connection = 'mysql';

	protected $table = 'imagens_oem';

	protected $primaryKey = 'codigo';

	protected $fillable = ['path', 'oemcodigo'];

	public function site() {
		return $this->belongsTo('Messtechnik\Models\Oem', 'oemcodigo', 'codigo');
	}
}
