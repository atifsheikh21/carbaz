<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'vehicle_company_postal_code')) {
                $table->string('vehicle_company_postal_code')->nullable()->after('vehicle_company_address');
            }

            if (!Schema::hasColumn('users', 'part_company_postal_code')) {
                $table->string('part_company_postal_code')->nullable()->after('part_company_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drops = [];

            foreach (['vehicle_company_postal_code', 'part_company_postal_code'] as $col) {
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
