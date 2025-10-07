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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->integer('obtained_marks');
            $table->integer('total_marks'); // Subject's total marks for this exam
            $table->decimal('percentage', 5, 2); // Calculated percentage
            $table->enum('status', ['pass', 'fail'])->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Ensure unique combination: one result per student per exam per subject
            $table->unique(['student_id', 'exam_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
