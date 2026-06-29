<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ad_reports')) {
            return;
        }

        Schema::create('ad_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('reportable_type');
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason', 255);
            $table->text('details')->nullable();
            $table->string('reporter_ip', 64)->nullable();
            $table->string('status', 32)->default('new');
            $table->timestamps();

            $table->index(['reportable_type', 'reportable_id']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_reports');
    }
};
