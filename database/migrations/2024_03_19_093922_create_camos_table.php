<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camos', static function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->unsignedBigInteger('owner_id');
            $table->string('contract');
            $table->unsignedBigInteger('cam_id');
            $table->string('aircraft');
            $table->string('description');
            $table->date('start_date');
            $table->date('finish_date');
            $table->string('location')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camos');
    }
};
