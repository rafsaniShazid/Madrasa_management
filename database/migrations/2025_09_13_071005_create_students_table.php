<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('session');
            $table->enum('class', [
                'play', 'nursery', 'first', 'second', 'third', 'fourth',
                'nazira', 'hifzul_quran'
            ]);
            $table->enum('student_type', ['new', 'old']);
            $table->enum('gender', ['male', 'female']);
            $table->enum('residence_status', ['resident', 'non-resident']);
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('date_of_birth');
            $table->string('nid_birth_no')->nullable();
            $table->string('nationality')->default('Bangladeshi');
            $table->string('blood_group')->nullable();
            $table->string('guardian_phone')->required();
            $table->string('sms_number')->required();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};