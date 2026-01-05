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
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->integer('user_id');
            $table->integer('subscription_plan_id');
            $table->string('plan_name');
            $table->decimal('plan_price', 8, 2);
            $table->string('expiration_date');
            $table->string('expiration');
            $table->string('max_car');
            $table->string('featured_car');
            $table->string('status')->default('inactive');
            $table->string('payment_method');
            $table->string('payment_status')->default('inactive');
            $table->string('transaction');
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
        Schema::dropIfExists('subscription_histories');
    }
};
