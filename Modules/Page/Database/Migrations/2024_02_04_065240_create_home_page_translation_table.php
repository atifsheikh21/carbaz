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
        Schema::create('home_page_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('home_page_id');
            $table->string('lang_code');
            $table->string('home1_intro_short_title')->nullable();
            $table->string('home1_intro_title')->nullable();
            $table->string('home2_intro_short_title')->nullable();
            $table->string('home2_intro_title')->nullable();
            $table->string('home3_intro_short_title')->nullable();
            $table->string('home3_intro_title')->nullable();
            $table->string('video_short_title')->nullable();
            $table->string('video_title')->nullable();
            $table->string('dealer_short_title')->nullable();
            $table->string('dealer_title')->nullable();
            $table->string('mobile_short_title')->nullable();
            $table->string('mobile_title')->nullable();
            $table->string('mobile_description')->nullable();
            $table->string('working_step_title1')->nullable();
            $table->string('working_step_title2')->nullable();
            $table->string('working_step_title3')->nullable();
            $table->string('working_step_title4')->nullable();
            $table->string('working_step_des1')->nullable();
            $table->string('working_step_des2')->nullable();
            $table->string('working_step_des3')->nullable();
            $table->string('working_step_des4')->nullable();
            $table->string('counter_title1')->nullable();
            $table->string('counter_title2')->nullable();
            $table->string('counter_title3')->nullable();
            $table->string('callus_title')->nullable();
            $table->string('callus_header1')->nullable();
            $table->string('callus_header2')->nullable();


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
        Schema::dropIfExists('home_page_translations');
    }
};
