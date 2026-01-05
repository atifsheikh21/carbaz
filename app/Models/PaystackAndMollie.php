<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Currency\app\Models\MultiCurrency;

class PaystackAndMollie extends Model
{
    use HasFactory;

    public function paystack_currency(){
        return $this->belongsTo(MultiCurrency::class, 'paystack_currency_id');
    }

    public function mollie_currency(){
        return $this->belongsTo(MultiCurrency::class, 'mollie_currency_id');
    }


}
