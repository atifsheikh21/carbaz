<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Blog\Database\factories\BlogCommentFactory::new();
    }

    public function blog(){
        return $this->belongsTo(Blog::class);
    }
}
