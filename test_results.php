<?php

use App\Models\Student;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Result;
use App\Models\ClassSubject;

// Test basic data counts
echo "=== DATA SUMMARY ===" . PHP_EOL;
echo "Subjects: " . Subject::count() . PHP_EOL;
echo "Exams: " . Exam::count() . PHP_EOL;
echo "Results: " . Result::count() . PHP_EOL;
echo "Class-Subjects: " . ClassSubject::count() . PHP_EOL;
echo PHP_EOL;

// Test a student's results
$student = Student::first();
if ($student) {
    echo "=== TESTING STUDENT: {$student->name} ===" . PHP_EOL;
    echo "Class: {$student->class}" . PHP_EOL;
    
    $results = $student->results()->with(['exam', 'subject'])->get();
    echo "Total Results: " . $results->count() . PHP_EOL;
    echo PHP_EOL;
    
    echo "=== SAMPLE RESULTS ===" . PHP_EOL;
    foreach ($results->take(5) as $result) {
        echo "{$result->exam->name} - {$result->subject->name}: ";
        echo "{$result->obtained_marks}/{$result->total_marks} ";
        echo "({$result->percentage}%) - {$result->status}" . PHP_EOL;
    }
}

echo PHP_EOL;
echo "=== TESTING SUBJECTS BY CLASS ===" . PHP_EOL;
$nurserySubjects = ClassSubject::where('class', 'nursery')
    ->with('subject')
    ->get();
    
echo "Nursery Class Subjects:" . PHP_EOL;
foreach ($nurserySubjects as $cs) {
    echo "- {$cs->subject->name} ({$cs->subject->code}) - ";
    echo "{$cs->subject->total_marks} marks, Pass: {$cs->subject->pass_marks}" . PHP_EOL;
}

echo PHP_EOL . "=== TEST COMPLETED SUCCESSFULLY! ===" . PHP_EOL;