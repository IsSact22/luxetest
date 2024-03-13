<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aircraft', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->foreignIdFor(\App\Models\AircraftModel::class)->constrained();
            $table->date('construction_date');
            $table->string('serial', 50);
            $table->string('registration', 10)->unique();
            $table->decimal('flight_hours', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircraft');
    }
};
