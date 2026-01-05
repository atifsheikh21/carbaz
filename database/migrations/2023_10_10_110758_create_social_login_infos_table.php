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
        Schema::create('social_login_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('is_facebook')->default(0);
            $table->text('facebook_client_id')->nullable();
            $table->text('facebook_secret_id')->nullable();
            $table->text('facebook_ca')->nullable();
            $table->integer('is_gmail')->default(0);
            $table->text('gmail_client_id')->nullable();
            $table->text('gmail_secret_id')->nullable();
            $table->text('facebook_redirect_url')->nullable();
            $table->text('gmail_redirect_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_login_infos');
    }
};
