<?php

namespace Modules\Car\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Car\Entities\CarTranslation;
use Modules\Brand\Entities\Brand;
use App\Models\User;
use Modules\Car\Entities\CarGallery;
use Modules\City\Entities\City;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $appends = ['title', 'description', 'video_description', 'address', 'seo_title', 'seo_description'];

    protected $hidden = ['front_translate'];

    protected static function newFactory()
    {
        return \Modules\Car\Database\factories\CarFactory::new();
    }

    public function dealer(){
        return $this->belongsTo(User::class, 'agent_id')->select(
            'id',
            'name',
            'email',
            'image',
            'phone',
            'is_dealer',
            'is_vehicle_seller',
            'vehicle_company_name',
            'vehicle_company_address',
            'is_part_seller',
            'part_company_name',
            'part_company_address'
        );
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(CarGallery::class, 'car_id');
    }

    public function translate(): HasOne
    {
        return $this->hasOne(CarTranslation::class, 'car_id', 'id')
            ->where('lang_code', admin_lang())
            ->orderByDesc('id');
    }

    public function front_translate(): HasOne
    {
        return $this->hasOne(CarTranslation::class, 'car_id', 'id')
            ->where('lang_code', front_lang())
            ->orderByDesc('id');
    }

    protected function translationValue(string $field)
    {
        if ($this->front_translate && !is_null($this->front_translate->{$field})) {
            return $this->front_translate->{$field};
        }

        if ($this->translate && !is_null($this->translate->{$field})) {
            return $this->translate->{$field};
        }

        return $this->attributes[$field] ?? null;
    }

    public function getTitleAttribute()
    {
        return $this->translationValue('title');
    }

    public function getDescriptionAttribute()
    {
        return $this->translationValue('description');
    }

    public function getVideoDescriptionAttribute()
    {
        return $this->translationValue('video_description');
    }

    public function getAddressAttribute()
    {
        return $this->translationValue('address');
    }

    public function getSeoTitleAttribute()
    {
        return $this->translationValue('seo_title');
    }

    public function getSeoDescriptionAttribute()
    {
        return $this->translationValue('seo_description');
    }
}
