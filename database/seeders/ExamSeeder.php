<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            1 => 'Play',
            2 => 'Nursery',
            3 => 'First',
            4 => 'Second',
            5 => 'Third',
            6 => 'Fourth',
            7 => 'Nazira',
            8 => 'Hifzul Quran'
        ];
        $session = '2024-2025';
        
        foreach ($classes as $classId => $className) {
            // First Term Exam (Completed)
            Exam::create([
                'name' => "First Term Exam 2024 - {$className}",
                'class_id' => $classId,
                'session' => $session,
                'exam_date' => Carbon::create(2024, 11, 15), // November 15, 2024
                'type' => 'first_term',
                'status' => 'completed',
                'description' => "First term examination for {$className} class students"
            ]);
            
            // Second Term Exam (Completed)
            Exam::create([
                'name' => "Second Term Exam 2025 - {$className}",
                'class_id' => $classId,
                'session' => $session,
                'exam_date' => Carbon::create(2025, 3, 20), // March 20, 2025
                'type' => 'second_term',
                'status' => 'completed',
                'description' => "Second term examination for {$className} class students"
            ]);
        }
    }
}
