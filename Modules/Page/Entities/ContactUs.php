<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Page\Entities\ContactUsTranslation;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Page\Database\factories\ContactUsFactory::new();
    }

    protected $hidden = ['front_translate'];

    protected $appends = ['title', 'description', 'address'];

    public function translate(){
        return $this->belongsTo(ContactUsTranslation::class, 'id', 'contact_us_id')->where('lang_code' , admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(ContactUsTranslation::class, 'id', 'contact_us_id')->where('lang_code' , front_lang());
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

    public function getAddressAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->address;
       }else{
           return $this->translate->address;
       }
    }

    
}
