<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarPart extends Model
{
    protected $table = 'car_parts';

    protected $fillable = [
        'agent_id',
        'brand_id',
        'city_id',
        'slug',
        'condition',
        'regular_price',
        'offer_price',
        'part_number',
        'compatibility',
        'thumb_image',
        'status',
        'approved_by_admin',
        'expired_date',
    ];

    protected $casts = [
        'expired_date' => 'datetime',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(\Modules\Brand\Entities\Brand::class, 'brand_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(\Modules\City\Entities\City::class, 'city_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CarPartTranslation::class, 'car_part_id');
    }

    public function translate(): HasOne
    {
        return $this->hasOne(CarPartTranslation::class, 'car_part_id', 'id')
            ->where('lang_code', function_exists('admin_lang') ? admin_lang() : 'en');
    }

    public function frontTranslate(): HasOne
    {
        return $this->hasOne(CarPartTranslation::class, 'car_part_id', 'id')
            ->where('lang_code', function_exists('front_lang') ? front_lang() : 'en');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(CarPartGallery::class, 'car_part_id');
    }
}
