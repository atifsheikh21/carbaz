<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarPartRequestVote extends Model
{
    protected $table = 'car_part_request_votes';

    protected $fillable = ['car_part_request_id', 'user_id', 'type'];
}
