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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('contact_message_mail')->nullable();
            $table->string('send_contact_message')->default('enable');
            $table->string('save_contact_message')->default('enable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('contact_message_mail');
            $table->dropColumn('send_contact_message');
            $table->dropColumn('save_contact_message');
        });
    }
};
