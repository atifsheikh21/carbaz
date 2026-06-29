<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cars') || !Schema::hasColumn('cars', 'brand_id')) {
            return;
        }

        DB::statement('ALTER TABLE `cars` MODIFY `brand_id` INT NULL');
    }

    public function down(): void
    {
        if (!Schema::hasTable('cars') || !Schema::hasColumn('cars', 'brand_id')) {
            return;
        }

        DB::table('cars')->whereNull('brand_id')->update(['brand_id' => 0]);
        DB::statement('ALTER TABLE `cars` MODIFY `brand_id` INT NOT NULL');
    }
};
