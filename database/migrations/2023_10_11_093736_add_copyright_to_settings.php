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
            $table->string('copyright')->nullable();
            $table->string('twitter')->nullable();
            $table->string('behance')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('copyright');
            $table->dropColumn('twitter');
            $table->dropColumn('behance');
            $table->dropColumn('instagram');
            $table->dropColumn('linkedin');
            $table->dropColumn('facebook');
        });
    }
};
