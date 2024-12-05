<?php

use App\Models\ModelAircraft;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aircrafts', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ModelAircraft::class)->constrained();
            $table->string('register');
            $table->string('serial');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['model_aircraft_id', 'register', 'serial'], 'unique_aircraft');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircrafts');
    }
};
