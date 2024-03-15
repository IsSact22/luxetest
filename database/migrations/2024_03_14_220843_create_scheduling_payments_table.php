<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduling_payments', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->string('interval');
            $table->decimal('mount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduling_payments');
    }
};
