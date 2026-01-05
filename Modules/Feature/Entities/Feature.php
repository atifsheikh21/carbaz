<?php

namespace Modules\Feature\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Feature\Entities\FeatureTranslation;
use Modules\Car\Entities\Car;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $appends = ['name'];

    protected $hidden = ['front_translate'];

    protected static function newFactory()
    {
        return \Modules\Feature\Database\factories\FeatureFactory::new();
    }

    public function translate(){
        return $this->belongsTo(FeatureTranslation::class, 'id', 'feature_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(FeatureTranslation::class, 'id', 'feature_id')->where('lang_code', front_lang());
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
