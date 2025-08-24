<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Author extends Model implements HasMedia
{
    //
    use InteractsWithMedia;
    protected $fillable = ['name', 'bio', 'photo', 'is_active'];
}
