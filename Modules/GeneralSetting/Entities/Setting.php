<?php

namespace Modules\GeneralSetting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SettingTranslation;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $hidden = ['front_translate'];

    protected $appends = ['about_us', 'address', 'copyright'];
    
    protected static function newFactory()
    {
        return \Modules\GeneralSetting\Database\factories\SettingFactory::new();
    }

    public function translate(){
        return $this->belongsTo(SettingTranslation::class, 'id', 'setting_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(SettingTranslation::class, 'id', 'setting_id')->where('lang_code', front_lang());
    }

    public function getAboutUsAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->about_us;
       }else{
           return $this->translate->about_us;
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

    public function getCopyrightAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->copyright;
       }else{
           return $this->translate->copyright;
       }
    }
}
