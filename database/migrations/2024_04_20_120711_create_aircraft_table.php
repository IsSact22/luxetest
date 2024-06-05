<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aircrafts', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ModelAircraft::class)->constrained();
            $table->string('register')->unique();
            $table->string('serial')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircrafts');
    }
};
