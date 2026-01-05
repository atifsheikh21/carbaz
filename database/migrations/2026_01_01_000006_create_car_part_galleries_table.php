<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_part_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_part_id')->constrained('car_parts')->cascadeOnDelete();
            $table->string('image');
            $table->timestamps();

            $table->index(['car_part_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_part_galleries');
    }
};
