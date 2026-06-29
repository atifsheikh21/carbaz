<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarPartRequestReply extends Model
{
    protected $table = 'car_part_request_replies';

    protected $fillable = [
        'car_part_request_id',
        'user_id',
        'message',
        'offer_price',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(CarPartRequest::class, 'car_part_request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(CarPartRequestReplyVote::class, 'car_part_request_reply_id');
    }
}
