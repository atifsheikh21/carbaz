<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_vehicle_seller')) {
                $table->boolean('is_vehicle_seller')->default(0)->after('is_dealer');
            }

            if (!Schema::hasColumn('users', 'is_part_seller')) {
                $table->boolean('is_part_seller')->default(0)->after('is_vehicle_seller');
            }

            if (!Schema::hasColumn('users', 'vehicle_company_name')) {
                $table->string('vehicle_company_name')->nullable()->after('is_part_seller');
            }

            if (!Schema::hasColumn('users', 'vehicle_company_address')) {
                $table->string('vehicle_company_address')->nullable()->after('vehicle_company_name');
            }

            if (!Schema::hasColumn('users', 'part_company_name')) {
                $table->string('part_company_name')->nullable()->after('vehicle_company_address');
            }

            if (!Schema::hasColumn('users', 'part_company_address')) {
                $table->string('part_company_address')->nullable()->after('part_company_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drops = [];

            foreach ([
                'is_vehicle_seller',
                'is_part_seller',
                'vehicle_company_name',
                'vehicle_company_address',
                'part_company_name',
                'part_company_address',
            ] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $drops[] = $col;
                }
            }

            if (!empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};
