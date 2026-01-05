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
        if (Schema::hasTable('google_recaptchas')) {
            return;
        }

        Schema::create('google_recaptchas', function (Blueprint $table) {
            $table->id();
            $table->string('site_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->boolean('recaptcha_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_recaptchas');
    }
};
