<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('client');
            $table->unsignedBigInteger('project_manager');
            $table->string('contract')->unique();
            $table->string('aircraft');
            $table->string('description');
            $table->date('start_date');
            $table->date('end_estimated');
            $table->date('finish_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client')->references('id')->on('users');
            $table->foreign('project_manager')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
