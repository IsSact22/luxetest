<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surcharge_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Project::class)->constrained();
            $table->decimal('gum_ecotax_labor', 8, 2);
            $table->decimal('gum_ecotax_material', 8, 2);
            $table->decimal('discount_labor', 8, 2);
            $table->decimal('discount_material', 8, 2);
            $table->decimal('nte_discount_labor', 8, 2);
            $table->decimal('nte_discount_material', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surcharge_discounts');
    }
};
