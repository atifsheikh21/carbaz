<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Currency\app\Models\MultiCurrency;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('multi_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_name');
            $table->string('country_code');
            $table->string('currency_code');
            $table->string('currency_icon');
            $table->string('is_default')->defualt('no');
            $table->decimal('currency_rate', 8, 2);
            $table->string('currency_position')->default('before_price');
            $table->string('status')->defualt('active');
            $table->timestamps();
        });

        if(!MultiCurrency::first()){
            $currency = new MultiCurrency();
            $currency->currency_name = '$-USD';
            $currency->country_code = 'USD';
            $currency->currency_code = 'USD';
            $currency->currency_icon = '$';
            $currency->is_default = 'yes';
            $currency->currency_rate = 1;
            $currency->currency_position = 'before_price';
            $currency->status = 'active';
            $currency->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multi_currencies');
    }
};
