<?php

namespace Modules\Brand\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Brand\Entities\BrandTranslation;
use Modules\Car\Entities\Car;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $appends = ['name', 'total_car'];

    protected $hidden = ['front_translate', 'cars'];

    public function cars(){
        return $this->hasMany(Car::class, 'brand_id');
    }

    public function getTotalCarAttribute()
    {
        return $this->cars->count();
    }

    public function translate(){
        return $this->belongsTo(BrandTranslation::class, 'id', 'brand_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(BrandTranslation::class, 'id', 'brand_id')->where('lang_code', front_lang());
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
