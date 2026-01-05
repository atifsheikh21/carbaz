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
        Schema::table('cars', function (Blueprint $table) {
            $table->string('motorcheck_reg')->nullable();
            $table->string('motorcheck_make')->nullable();
            $table->string('motorcheck_model')->nullable();
            $table->string('motorcheck_version')->nullable();
            $table->string('motorcheck_body')->nullable();
            $table->integer('motorcheck_doors')->nullable();
            $table->date('motorcheck_reg_date')->nullable();
            $table->integer('motorcheck_engine_cc')->nullable();
            $table->string('motorcheck_colour')->nullable();
            $table->string('motorcheck_fuel')->nullable();
            $table->string('motorcheck_transmission')->nullable();
            $table->integer('motorcheck_no_of_owners')->nullable();
            $table->string('motorcheck_tax_class')->nullable();
            $table->date('motorcheck_tax_expiry_date')->nullable();
            $table->date('motorcheck_nct_expiry_date')->nullable();
            $table->integer('motorcheck_co2_emissions')->nullable();
            $table->date('motorcheck_last_date_of_sale')->nullable();
            $table->longText('motorcheck_raw')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'motorcheck_reg',
                'motorcheck_make',
                'motorcheck_model',
                'motorcheck_version',
                'motorcheck_body',
                'motorcheck_doors',
                'motorcheck_reg_date',
                'motorcheck_engine_cc',
                'motorcheck_colour',
                'motorcheck_fuel',
                'motorcheck_transmission',
                'motorcheck_no_of_owners',
                'motorcheck_tax_class',
                'motorcheck_tax_expiry_date',
                'motorcheck_nct_expiry_date',
                'motorcheck_co2_emissions',
                'motorcheck_last_date_of_sale',
                'motorcheck_raw',
            ]);
        });
    }
};
