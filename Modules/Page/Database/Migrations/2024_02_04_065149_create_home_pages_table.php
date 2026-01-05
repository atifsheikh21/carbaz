<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();
            $table->string('home1_intro_image')->nullable();
            $table->string('home2_intro_image')->nullable();
            $table->string('home3_intro_image')->nullable();
            $table->string('video_bg_image')->nullable();
            $table->string('video_id')->nullable();
            $table->string('dealer_bg_image')->nullable();
            $table->string('dealer_foreground_image')->nullable();
            $table->string('mobile_user_image')->nullable();
            $table->string('mobile_app_image')->nullable();
            $table->string('mobile_app_image2')->nullable();
            $table->string('mobile_playstore')->nullable();
            $table->string('mobile_appstore')->nullable();
            $table->string('mobile_total_download')->nullable();
            $table->string('working_step_icon1')->nullable();
            $table->string('working_step_icon2')->nullable();
            $table->string('working_step_icon3')->nullable();
            $table->string('working_step_icon4')->nullable();
            $table->string('counter_icon1')->nullable();
            $table->string('counter_icon2')->nullable();
            $table->string('counter_icon3')->nullable();
            $table->string('counter_qty1')->nullable();
            $table->string('counter_qty2')->nullable();
            $table->string('counter_qty3')->nullable();
            $table->string('callus_phone')->nullable();
            $table->string('callus_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_pages');
    }
};
