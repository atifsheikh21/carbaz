<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_parts', function (Blueprint $table) {
            if (!Schema::hasColumn('car_parts', 'car_model')) {
                $table->string('car_model')->nullable()->after('brand_id');
            }

            if (!Schema::hasColumn('car_parts', 'from_year')) {
                $table->integer('from_year')->nullable()->after('car_model');
            }

            if (!Schema::hasColumn('car_parts', 'to_year')) {
                $table->integer('to_year')->nullable()->after('from_year');
            }

            if (!Schema::hasColumn('car_parts', 'warranty_months')) {
                $table->unsignedTinyInteger('warranty_months')->nullable()->after('to_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('car_parts', function (Blueprint $table) {
            $drops = [];

            foreach (['car_model', 'from_year', 'to_year', 'warranty_months'] as $col) {
                if (Schema::hasColumn('car_parts', $col)) {
                    $drops[] = $col;
                }
            }

            if (!empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};
