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
        Schema::table('cars', function (Blueprint $table) {
            // Add composite indexes for common queries
            $table->index(['status', 'approved_by_admin', 'expired_date'], 'cars_status_approved_expired');
            $table->index(['condition', 'status', 'approved_by_admin'], 'cars_condition_status_approved');
            $table->index(['is_featured', 'status', 'approved_by_admin'], 'cars_featured_status_approved');
            $table->index(['brand_id', 'status'], 'cars_brand_status');
            $table->index(['dealer_id', 'status'], 'cars_dealer_status');
            
            // Add indexes for year filtering
            $table->index('year', 'cars_year');
            $table->index('from_year', 'cars_from_year');
            $table->index('to_year', 'cars_to_year');
            $table->index('motorcheck_year', 'cars_motorcheck_year');
            
            // Add indexes for numeric fields used in filtering
            $table->index('mileage', 'cars_mileage');
            $table->index('regular_price', 'cars_regular_price');
            $table->index('offer_price', 'cars_offer_price');
            
            // Add index for JSON field (if supported)
            if (DB::connection()->getConfig('driver') === 'mysql') {
                $table->rawIndex('(CAST(motorcheck_raw AS CHAR(255)))', 'cars_motorcheck_raw_index');
            }
        });

        Schema::table('car_parts', function (Blueprint $table) {
            // Add composite indexes for common queries
            $table->index(['status', 'approved_by_admin', 'expired_date'], 'parts_status_approved_expired');
            $table->index(['brand_id', 'status'], 'parts_brand_status');
            
            // Add indexes for year filtering
            $table->index('year', 'parts_year');
            $table->index('from_year', 'parts_from_year');
            $table->index('to_year', 'parts_to_year');
        });

        Schema::table('users', function (Blueprint $table) {
            // Add indexes for user queries
            $table->index(['status', 'is_banned', 'is_dealer'], 'users_status_banned_dealer');
            $table->index('email_verified_at', 'users_email_verified');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'blogs_status_created');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->index(['status', 'name'], 'brands_status_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex('cars_status_approved_expired');
            $table->dropIndex('cars_condition_status_approved');
            $table->dropIndex('cars_featured_status_approved');
            $table->dropIndex('cars_brand_status');
            $table->dropIndex('cars_dealer_status');
            $table->dropIndex('cars_year');
            $table->dropIndex('cars_from_year');
            $table->dropIndex('cars_to_year');
            $table->dropIndex('cars_motorcheck_year');
            $table->dropIndex('cars_mileage');
            $table->dropIndex('cars_regular_price');
            $table->dropIndex('cars_offer_price');
            $table->dropRawIndex('cars_motorcheck_raw_index');
        });

        Schema::table('car_parts', function (Blueprint $table) {
            $table->dropIndex('parts_status_approved_expired');
            $table->dropIndex('parts_brand_status');
            $table->dropIndex('parts_year');
            $table->dropIndex('parts_from_year');
            $table->dropIndex('parts_to_year');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_status_banned_dealer');
            $table->dropIndex('users_email_verified');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex('blogs_status_created');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex('brands_status_name');
        });
    }
};
