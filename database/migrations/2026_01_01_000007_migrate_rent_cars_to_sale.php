<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cars')) {
            return;
        }

        if (!Schema::hasColumn('cars', 'purpose')) {
            return;
        }

        DB::table('cars')
            ->where('purpose', 'Rent')
            ->update([
                'purpose' => 'Sale',
                'rent_period' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // intentionally no-op
    }
};
