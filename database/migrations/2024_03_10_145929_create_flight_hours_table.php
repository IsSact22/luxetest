<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('flight_hours');
        Schema::create('flight_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Aircraft::class)->constrained();
            $table->date('flight_date');
            $table->decimal('flight_hours', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_hours');
    }
};
