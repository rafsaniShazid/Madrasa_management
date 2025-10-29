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
        Schema::create('meal_rates', function (Blueprint $table) {
            $table->id();
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner']);
            $table->decimal('rate', 8, 2);
            $table->date('effective_from');
            $table->timestamps();

            // Ensure one rate per meal type per effective date
            $table->unique(['meal_type', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_rates');
    }
};
