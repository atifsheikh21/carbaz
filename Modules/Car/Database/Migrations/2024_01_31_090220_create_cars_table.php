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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id')->default(0);
            $table->integer('brand_id');
            $table->integer('city_id');
            $table->string('thumb_image');
            $table->string('slug');
            $table->text('features')->nullable();
            $table->string('purpose')->nullable();
            $table->string('condition');
            $table->bigInteger('total_view')->default(0);
            $table->decimal('regular_price', 8, 2);
            $table->decimal('offer_price', 8, 2)->nullable();
            $table->string('video_id')->nullable();
            $table->string('video_image')->nullable();
            $table->text('google_map')->nullable();
            $table->string('body_type')->nullable();
            $table->string('engine_size')->nullable();
            $table->string('drive')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('exterior_color')->nullable();
            $table->string('year')->nullable();
            $table->string('mileage')->nullable();
            $table->string('number_of_owner')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('seller_type')->nullable();
            $table->string('expired_date')->nullable();
            $table->string('is_featured')->default('disable');
            $table->string('status')->default('disable');
            $table->string('approved_by_admin')->default('pending');
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
        Schema::dropIfExists('cars');
    }
};
