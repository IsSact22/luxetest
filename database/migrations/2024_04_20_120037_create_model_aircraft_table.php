<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_aircrafts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\BrandAircraft::class)->constrained();
            $table->foreignIdFor(\App\Models\EngineType::class)->constrained();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_aircrafts');
    }
};
