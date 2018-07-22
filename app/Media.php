<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';

    protected $fillable = ['name', 'mime_type', 'size', 'path', 'thumbnail_path'];

}
