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
        if (Schema::hasTable('tawk_chats')) {
            return;
        }
        Schema::create('tawk_chats', function (Blueprint $table) {
            $table->id();
            $table->string('property_id')->nullable();
            $table->string('widget_id')->nullable();
            $table->boolean('tawk_chat_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tawk_chats');
    }
};
