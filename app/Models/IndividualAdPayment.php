<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualAdPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'transaction_id',
        'consumed_at',
    ];

    protected $casts = [
        'consumed_at' => 'datetime',
    ];
}
