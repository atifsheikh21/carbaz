<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HiddenAd extends Model
{
    protected $table = 'hidden_ads';

    protected $fillable = [
        'user_id',
        'hideable_type',
        'hideable_id',
        'ad_report_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hideable(): MorphTo
    {
        return $this->morphTo();
    }

    public function adReport(): BelongsTo
    {
        return $this->belongsTo(AdReport::class, 'ad_report_id');
    }
}
