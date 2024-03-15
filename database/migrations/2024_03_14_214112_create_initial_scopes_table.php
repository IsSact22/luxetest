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
        Schema::create('initial_scopes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Project::class)->constrained();
            $table->decimal('labor', 8, 2);
            $table->decimal('material', 8, 2);
            $table->decimal('adjusted_labor', 8, 2);
            $table->decimal('adjusted_material', 8, 2);
            $table->decimal('total_labor', 8, 2);
            $table->decimal('total_material', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_scopes');
    }
};
