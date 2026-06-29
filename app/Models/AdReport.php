<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdReport extends Model
{
    protected $table = 'ad_reports';

    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'details',
        'reporter_ip',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}
