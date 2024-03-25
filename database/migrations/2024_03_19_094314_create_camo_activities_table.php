<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camo_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Camo::class)->constrained();
            $table->boolean('required')->default(false);
            $table->date('date')->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('status')->default('pending');
            $table->text('comments')->nullable();
            $table->decimal('labor_mount', 8, 2)->default(0);
            $table->decimal('material_mount', 8, 2)->default(0);
            $table->text('material_information')->nullable();
            $table->string('awr')->nullable()->index();
            $table->string('approval_status')->default('pending')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camo_activities');
    }
};
