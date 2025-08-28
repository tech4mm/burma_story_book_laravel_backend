<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Book extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'author_id',
        'category_id',
        'is_featured',
        'views',
        'pages',
        'is_free',
        'published_at'
    ];
    // public function author()
    // {
    //     return $this->belongsTo(User::class, 'author_id');
    // }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('book')
            ->useDisk('s3');   // 👈 Force this collection to S3
    }
    public function author()
{
    return $this->belongsTo(\App\Models\Author::class);
}
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function readers()
    {
        return $this->hasMany(ReadBook::class);
    }
}
