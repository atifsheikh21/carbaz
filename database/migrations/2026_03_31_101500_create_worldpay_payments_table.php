<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worldpay_payments', function (Blueprint $table) {
            $table->id();
            $table->string('service_key')->nullable();
            $table->string('client_key')->nullable();
            $table->unsignedBigInteger('currency_id')->default(0);
            $table->integer('status')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worldpay_payments');
    }
};
