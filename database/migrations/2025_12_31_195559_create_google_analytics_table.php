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
        if (Schema::hasTable('google_analytics')) {
            return;
        }

        Schema::create('google_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_id')->nullable();
            $table->boolean('analytics_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_analytics');
    }
};
