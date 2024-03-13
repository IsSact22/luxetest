<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aircraft_models', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Manufacturer::class)->constrained();
            $table->string('name');
            $table->enum('category', [
                'aircraft',
                'rotorcraft',
                'powered-lift',
            ])->default('aircraft');
            $table->enum('class', [
                'land',
                'sea',
                'helicopter',
                'gyroplane',
                'powered-parachuted-land',
                'powered-parachuted-sea',
            ])->default('land');
            $table->enum('motor_type', ['piston', 'turbofan', 'turbine']);
            $table->unsignedInteger('motor_qty')->default(1);
            $table->unsignedInteger('passenger_qty');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircraft_models');
    }
};
