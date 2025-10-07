<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Result;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassSubject;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        // Get some sample students (assuming they exist)
        $students = Student::limit(10)->get();
        
        if ($students->isEmpty()) {
            $this->command->info('No students found. Please run StudentSeeder first.');
            return;
        }
        
        // Get completed exams (first term)
        $completedExams = Exam::where('status', 'completed')->get();
        
        foreach ($students as $student) {
            foreach ($completedExams as $exam) {
                // Only create results for exams matching student's class
                if ($exam->class !== $student->class) {
                    continue;
                }
                
                // Get subjects for this class
                $classSubjects = ClassSubject::where('class', $exam->class)
                    ->where('is_active', true)
                    ->with('subject')
                    ->get();
                
                foreach ($classSubjects as $classSubject) {
                    $subject = $classSubject->subject;
                    
                    // Generate random marks (60-95% of total marks for variety)
                    $percentage = fake()->numberBetween(45, 95);
                    $obtainedMarks = round(($percentage / 100) * $subject->total_marks);
                    
                    $result = Result::create([
                        'student_id' => $student->student_id,
                        'exam_id' => $exam->id,
                        'subject_id' => $subject->id,
                        'obtained_marks' => $obtainedMarks,
                        'total_marks' => $subject->total_marks,
                        'percentage' => 0, // Will be calculated
                        'status' => null, // Will be calculated
                        'remarks' => $percentage >= 80 ? 'Excellent' : ($percentage >= 60 ? 'Good' : 'Needs improvement')
                    ]);
                    
                    // Use the model's calculation methods
                    $result->updateCalculatedFields();
                }
            }
        }
    }
}
