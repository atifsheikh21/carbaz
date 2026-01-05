<?php

namespace Modules\BannerSlider\Entities;

use Modules\Car\Entities\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BannerSlider\Database\factories\BannerSliderFactory;

class BannerSlider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

}
