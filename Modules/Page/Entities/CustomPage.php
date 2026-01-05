<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $appends = ['page_name','description'];

    protected $hidden = ['front_translate'];

    protected static function newFactory()
    {
        return \Modules\Page\Database\factories\CustomPageFactory::new();
    }


    public function translate(){
        return $this->belongsTo(CustomPageTranslation::class, 'id', 'custom_page_id')->where('lang_code' , admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(CustomPageTranslation::class, 'id', 'custom_page_id')->where('lang_code' , front_lang());
    }

    public function getPageNameAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->page_name;
       }else{
           return $this->translate->page_name;
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


}
