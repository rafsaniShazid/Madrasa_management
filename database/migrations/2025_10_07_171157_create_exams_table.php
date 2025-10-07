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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "First Term Exam 2024"
            $table->enum('class', [
                'play', 'nursery', 'first', 'second', 'third', 'fourth',
                'nazira', 'hifzul_quran'
            ]);
            $table->string('session'); // e.g., "2024-2025"
            $table->date('exam_date');
            $table->enum('type', [ 'first_term', 'second_term', 'final']);
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
