<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Car\Entities\Car;

class Review extends Model
{
    use HasFactory;


    public function user(){
        return $this->belongsTo(User::class)->select('id', 'image', 'email', 'name', 'designation');
    }

    public function car(){
        return $this->belongsTo(Car::class)->select('id', 'thumb_image', 'slug', 'agent_id');
    }


}
