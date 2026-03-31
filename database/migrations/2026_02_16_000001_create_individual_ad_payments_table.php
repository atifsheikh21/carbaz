<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('individual_ad_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('car_id')->nullable();

            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('EUR');

            $table->string('payment_method')->default('Stripe');
            $table->string('status')->default('success');

            $table->string('transaction_id')->nullable();
            $table->timestamp('consumed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status', 'consumed_at']);
            $table->index(['car_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_ad_payments');
    }
};
