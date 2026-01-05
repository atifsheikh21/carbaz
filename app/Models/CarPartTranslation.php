<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarPartTranslation extends Model
{
    protected $table = 'car_part_translations';

    protected $fillable = [
        'car_part_id',
        'lang_code',
        'title',
        'description',
        'address',
        'google_map',
        'seo_title',
        'seo_description',
    ];

    public function carPart(): BelongsTo
    {
        return $this->belongsTo(CarPart::class, 'car_part_id');
    }
}
