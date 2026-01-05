<?php

namespace Modules\Country\Entities;

use Modules\Car\Entities\Car;
use Modules\Listing\Entities\Listing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [];


    protected $appends = ['total_listing'];


    public function cars(){
        return $this->hasMany(Car::class, 'country_id');
    }



    public function getTotalListingAttribute()
    {
        return $this->cars()->count();
    }


}
