<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogImage extends Model
{
    use SoftDeletes;

     protected $fillable = [
        'blog_id',
        'image_name',
        'image_url',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
