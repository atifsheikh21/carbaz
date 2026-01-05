<?php

namespace Modules\Testimonial\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Testimonial\Entities\TestimonialTranslation;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $hidden = ['front_translate'];

    protected $appends = ['name', 'designation', 'comment'];

    protected static function newFactory()
    {
        return \Modules\Testimonial\Database\factories\TestimonialFactory::new();
    }

    public function translate(){
        return $this->belongsTo(TestimonialTranslation::class, 'id', 'testimonial_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(TestimonialTranslation::class, 'id', 'testimonial_id')->where('lang_code', front_lang());
    }

    public function getNameAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->name;
       }else{
           return $this->translate->name;
       }
    }

    public function getDesignationAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->designation;
       }else{
           return $this->translate->designation;
       }
    }

    public function getCommentAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->comment;
       }else{
           return $this->translate->comment;
       }
    }
}
