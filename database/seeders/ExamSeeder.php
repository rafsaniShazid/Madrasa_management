<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ['play', 'nursery', 'first', 'second', 'third', 'fourth', 'nazira', 'hifzul_quran'];
        $session = '2024-2025';
        
        foreach ($classes as $class) {
            // First Term Exam
            Exam::create([
                'name' => 'First Term Exam 2024',
                'class' => $class,
                'session' => $session,
                'exam_date' => Carbon::create(2024, 11, 15), // November 15, 2024
                'type' => 'first_term',
                'status' => 'completed',
                'description' => "First term examination for {$class} class students"
            ]);
            
            // Second Term Exam
            Exam::create([
                'name' => 'Second Term Exam 2025',
                'class' => $class,
                'session' => $session,
                'exam_date' => Carbon::create(2025, 3, 20), // March 20, 2025
                'type' => 'second_term',
                'status' => 'scheduled',
                'description' => "Second term examination for {$class} class students"
            ]);
            
            // Final Exam
            Exam::create([
                'name' => 'Final Exam 2025',
                'class' => $class,
                'session' => $session,
                'exam_date' => Carbon::create(2025, 6, 10), // June 10, 2025
                'type' => 'final',
                'status' => 'scheduled',
                'description' => "Final examination for {$class} class students"
            ]);
        }
    }
}
