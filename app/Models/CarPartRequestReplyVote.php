<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarPartRequestReplyVote extends Model
{
    protected $table = 'car_part_request_reply_votes';

    protected $fillable = ['car_part_request_reply_id', 'user_id', 'type'];
}
