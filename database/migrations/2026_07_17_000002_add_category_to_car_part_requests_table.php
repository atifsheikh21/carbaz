<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('car_part_requests') || Schema::hasColumn('car_part_requests', 'category')) {
            return;
        }

        Schema::table('car_part_requests', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('car_part_requests') || ! Schema::hasColumn('car_part_requests', 'category')) {
            return;
        }

        Schema::table('car_part_requests', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
