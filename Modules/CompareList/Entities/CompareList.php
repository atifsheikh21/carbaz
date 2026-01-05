<?php

namespace Modules\CompareList\Entities;

use Modules\Car\Entities\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompareList\Database\factories\CompareListFactory;

class CompareList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'car_id'];


    public function car(){
        return $this->belongsTo(Car::class);
    }
}
