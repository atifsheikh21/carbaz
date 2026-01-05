<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Subscription\Database\factories\SubscriptionHistoryFactory::new();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
