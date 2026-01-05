<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Page\Database\factories\AboutUsFactory::new();
    }

    protected $hidden = ['front_translate'];

    protected $appends = ['header', 'title','description','total_car','total_car_title','total_review','total_review_title'];

    public function translate(){
        return $this->belongsTo(AboutUsTranslation::class, 'id', 'about_us_id')->where('lang_code' , admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(AboutUsTranslation::class, 'id', 'about_us_id')->where('lang_code' , front_lang());
    }

    public function getHeaderAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->header;
       }else{
           return $this->translate->header;
       }
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

    public function getTotalCarAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->total_car;
       }else{
           return $this->translate->total_car;
       }
    }

    public function getTotalCarTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->total_car_title;
       }else{
           return $this->translate->total_car_title;
       }
    }

    public function getTotalReviewAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->total_review;
       }else{
           return $this->translate->total_review;
       }
    }

    public function getTotalReviewTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->total_review_title;
       }else{
           return $this->translate->total_review_title;
       }
    }






}
