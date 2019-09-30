<?php

namespace Messtechnik\Models;

use Illuminate\Database\Eloquent\Model;

class NewPost extends Model
{
    protected $table = 'new_posts';

    protected $fillable = [
        'titulo', 'descricao', 'imagem_dir'
    ];

}
