<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('car_part_request_replies')) {
            return;
        }

        Schema::create('car_part_request_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_part_request_id')->constrained('car_part_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->text('message');
            $table->decimal('offer_price', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_part_request_replies');
    }
};
