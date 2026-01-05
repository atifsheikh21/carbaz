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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['enable', 'disable'])->default('enable');
            }
            if (!Schema::hasColumn('users', 'is_banned')) {
                $table->enum('is_banned', ['yes', 'no'])->default('no');
            }
            if (!Schema::hasColumn('users', 'is_dealer')) {
                $table->boolean('is_dealer')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drops = [];
            foreach (['status', 'is_banned', 'is_dealer'] as $col) {
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
