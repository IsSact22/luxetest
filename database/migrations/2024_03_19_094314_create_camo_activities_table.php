<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camo_activities', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Camo::class)->constrained();
            $table->unsignedBigInteger('labor_rate_id');
            $table->boolean('required')->default(false);
            $table->date('date')->nullable();
            $table->string('name');
            $table->text('description');
            $table->decimal('estimate_time', 5, 2);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('status')->default('pending');
            $table->text('comments')->nullable();
            $table->decimal('labor_mount', 8, 2)->nullable();
            $table->decimal('material_mount', 8, 2)->nullable();
            $table->text('material_information')->nullable();
            $table->string('awr')->nullable()->index();
            $table->string('approval_status')->nullable()->default('pending')->index();
            $table->unsignedInteger('priority')->default(3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camo_activities');
    }
};
