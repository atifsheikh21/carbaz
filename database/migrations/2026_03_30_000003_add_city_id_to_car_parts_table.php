<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_parts', function (Blueprint $table) {
            if (!Schema::hasColumn('car_parts', 'city_id')) {
                $table->foreignId('city_id')->nullable()->after('brand_id')->constrained('cities')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('car_parts', function (Blueprint $table) {
            if (Schema::hasColumn('car_parts', 'city_id')) {
                $table->dropConstrainedForeignId('city_id');
            }
        });
    }
};
