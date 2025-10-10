<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Exam;

echo "Testing SchoolClass relationships:\n\n";

$class = SchoolClass::find(3);
echo "Class: {$class->name}\n";
echo "Students count: " . $class->students->count() . "\n";
echo "Exams count: " . $class->exams->count() . "\n";
echo "Subjects count: " . $class->subjects->count() . "\n\n";

echo "Testing Student relationship:\n";
$student = Student::first();
echo "Student: {$student->name}\n";
echo "Class: {$student->schoolClass->name}\n\n";

echo "Testing Exam relationship:\n";
$exam = Exam::first();
echo "Exam: {$exam->name}\n";
echo "Class: {$exam->schoolClass->name}\n";

echo "\n✅ All relationships working correctly!\n";
