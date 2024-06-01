<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('labor_rates', static function (Blueprint $table) {
            $table->id();
            $table->morphs('rateable');
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->decimal('mount', 8, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labor_rates');
    }
};
