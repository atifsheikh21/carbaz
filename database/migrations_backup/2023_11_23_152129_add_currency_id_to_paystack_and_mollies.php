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
        Schema::table('paystack_and_mollies', function (Blueprint $table) {
            $table->integer('paystack_currency_id')->default(0);
            $table->integer('mollie_currency_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paystack_and_mollies', function (Blueprint $table) {
            $table->dropColumn('paystack_currency_id');
            $table->dropColumn('mollie_currency_id');
        });
    }
};
