<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hidden_ads')) {
            return;
        }

        Schema::create('hidden_ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('hideable_type');
            $table->unsignedBigInteger('hideable_id');
            $table->unsignedBigInteger('ad_report_id')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'hideable_type', 'hideable_id'], 'hidden_ads_unique_user_hideable');
            $table->index(['hideable_type', 'hideable_id']);
            $table->index(['ad_report_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hidden_ads');
    }
};
