<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_part_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_part_id')->constrained('car_parts')->cascadeOnDelete();
            $table->string('lang_code', 20);

            $table->string('title');
            $table->longText('description');

            $table->string('address')->nullable();
            $table->text('google_map')->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            $table->timestamps();

            $table->unique(['car_part_id', 'lang_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_part_translations');
    }
};
