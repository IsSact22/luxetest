<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camos', function (Blueprint $table) {
            $table->dropColumn('aircraft');
            $table->unsignedBigInteger('aircraft_id')->after('cam_id');
            $table->foreign('aircraft_id')
                ->references('id')
                ->on('aircrafts');
        });
    }

    public function down(): void
    {
        Schema::table('camos', function (Blueprint $table) {
            $table->dropForeign('camos_aircraft_id_foreign');
            $table->dropColumn('aircraft_id');
            $table->string('aircraft')->after('cam_id');
        });
    }
};
