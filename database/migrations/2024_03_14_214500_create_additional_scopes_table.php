<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('additional_scopes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Project::class)->constrained();
            $table->decimal('approved_labor', 8, 2);
            $table->decimal('pending_labor', 8, 2);
            $table->decimal('approved_material', 8, 2);
            $table->decimal('pending_material', 8, 2);
            $table->decimal('total_labor', 8, 2);
            $table->decimal('total_material', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_scopes');
    }
};
