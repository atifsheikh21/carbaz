<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarPartRequest extends Model
{
    protected $table = 'car_part_requests';

    protected $fillable = [
        'user_id',
        'title',
        'part_description',
        'car_make',
        'car_model',
        'car_year',
        'additional_notes',
        'image',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CarPartRequestReply::class, 'car_part_request_id');
    }
}
