<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Currency\app\Models\MultiCurrency;

class WorldpayPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_key',
        'client_key',
        'currency_id',
        'status',
        'image',
    ];

    public function currency()
    {
        return $this->belongsTo(MultiCurrency::class);
    }
}
