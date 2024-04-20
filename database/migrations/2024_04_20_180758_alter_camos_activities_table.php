<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camo_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('camo_activity_rate_id')->after('camo_id');
            $table->foreign('camo_activity_rate_id')->references('id')->on('camo_activity_rates');
        });
    }

    public function down(): void
    {
        Schema::table('camo_activities', function (Blueprint $table) {
            $table->dropForeign('camo_activities_camo_activity_rate_id_foreign');
        });
    }
};
