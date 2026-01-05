<?php

namespace Modules\City\Entities;

use Modules\Car\Entities\Car;
use Modules\Country\Entities\Country;
use Illuminate\Database\Eloquent\Model;
use Modules\City\Entities\CityTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $appends = ['name', 'total_car'];

    protected $hidden = ['front_translate', 'cars'];


    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function cars(){
        return $this->hasMany(Car::class, 'city_id');
    }

    public function getTotalCarAttribute()
    {
        return $this->cars->count();
    }


    public function translate(){
        return $this->belongsTo(CityTranslation::class, 'id', 'city_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(CityTranslation::class, 'id', 'city_id')->where('lang_code', front_lang());
    }

    public function getNameAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->name;
       }else{
           return $this->translate->name;
       }
    }
}
