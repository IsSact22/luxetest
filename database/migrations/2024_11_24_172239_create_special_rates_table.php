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
        Schema::create('special_rates', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('camo_activity_id')->constrained('camo_activities')->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('amount', 8, 2);
            $table->boolean('is_used')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_rates');
    }
};
