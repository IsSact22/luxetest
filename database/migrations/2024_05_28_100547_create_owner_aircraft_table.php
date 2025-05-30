<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owner_aircraft', static function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('aircraft_id');
            $table->timestamps();
            $table->primary(['owner_id', 'aircraft_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_aircraft');
    }
};
