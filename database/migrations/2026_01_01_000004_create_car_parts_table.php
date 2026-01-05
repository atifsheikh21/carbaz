<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();

            $table->string('slug')->unique();

            $table->string('condition')->default('Used');
            $table->decimal('regular_price', 12, 2)->default(0);
            $table->decimal('offer_price', 12, 2)->nullable();

            $table->string('part_number')->nullable();
            $table->string('compatibility')->nullable();

            $table->string('thumb_image')->nullable();

            $table->enum('status', ['enable', 'disable'])->default('enable');
            $table->enum('approved_by_admin', ['approved', 'pending'])->default('approved');

            $table->timestamp('expired_date')->nullable();

            $table->timestamps();

            $table->index(['agent_id', 'status', 'approved_by_admin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_parts');
    }
};
