<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarPartGallery extends Model
{
    protected $table = 'car_part_galleries';

    protected $fillable = [
        'car_part_id',
        'image',
    ];

    public function carPart(): BelongsTo
    {
        return $this->belongsTo(CarPart::class, 'car_part_id');
    }
}
