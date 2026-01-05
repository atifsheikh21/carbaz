<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin;
use Modules\Blog\Entities\BlogComment;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $hidden = ['front_translate', 'comments'];

    protected $appends = ['title', 'description', 'seo_title', 'seo_description', 'total_comment'];

    protected static function newFactory()
    {
        return \Modules\Blog\Database\factories\BlogFactory::new();
    }

    public function author(){
        return $this->belongsTo(Admin::class,'admin_id','id')->select('id','name','about_me','facebook','linkedin','twitter','instagram','image','designation');
    }

    public function category(){
        return $this->belongsTo(BlogCategory::class,'blog_category_id','id');
    }

    public function translations()
    {
        return $this->hasMany(BlogTranslation::class, 'blog_id');
    }

    public function translate(){
        return $this->belongsTo(BlogTranslation::class, 'id', 'blog_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(BlogTranslation::class, 'id', 'blog_id')->where('lang_code', front_lang());
    }

    public function getTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->title;
       }else{
           return $this->translate->title;
       }
    }

    public function getDescriptionAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->description;
       }else{
           return $this->translate->description;
       }
    }

    public function getSeoTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->seo_title;
       }else{
           return $this->translate->seo_title;
       }
    }

    public function getSeoDescriptionAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->seo_description;
       }else{
           return $this->translate->seo_description;
       }
    }

    public function getTotalCommentAttribute()
    {
        return $this->comments->where('status', 1)->count();
    }


    public function comments(){
        return $this->hasMany(BlogComment::class);
    }

}
