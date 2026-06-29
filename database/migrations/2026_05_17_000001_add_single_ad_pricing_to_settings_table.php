<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'single_ad_price')) {
                $table->decimal('single_ad_price', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('settings', 'single_ad_duration_days')) {
                $table->unsignedInteger('single_ad_duration_days')->default(30);
            }
            if (!Schema::hasColumn('settings', 'single_ad_pricing_enabled')) {
                $table->string('single_ad_pricing_enabled')->default('enable');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'single_ad_price')) {
                $table->dropColumn('single_ad_price');
            }
            if (Schema::hasColumn('settings', 'single_ad_duration_days')) {
                $table->dropColumn('single_ad_duration_days');
            }
            if (Schema::hasColumn('settings', 'single_ad_pricing_enabled')) {
                $table->dropColumn('single_ad_pricing_enabled');
            }
        });
    }
};
