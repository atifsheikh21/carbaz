<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('car_part_requests')) {
            return;
        }

        Schema::table('car_part_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('car_part_requests', 'image')) {
                $table->string('image')->nullable()->after('additional_notes');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('car_part_requests')) {
            return;
        }

        Schema::table('car_part_requests', function (Blueprint $table) {
            if (Schema::hasColumn('car_part_requests', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
