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
        Schema::table('camo_activities', static function (Blueprint $table) {
            $table->foreign('labor_rate_id', 'labor_rate_fk')->references('id')
                ->on('labor_rates')
                ->onDelete('cascade');
            $table->foreign('labor_rate_value_id', 'labor_value_fk')
                ->references('id')
                ->on('labor_rate_values')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('camo_activities', static function (Blueprint $table) {
            $table->dropForeign('labor_rate_fk');
            $table->dropForeign('labor_value_fk');
        });
    }
};
