<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Mathematics',
                'code' => 'MATH',
                'type' => 'theory',
                'total_marks' => 100,
                'pass_marks' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'English',
                'code' => 'ENG',
                'type' => 'theory',
                'total_marks' => 100,
                'pass_marks' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'Arabic',
                'code' => 'ARA',
                'type' => 'theory',
                'total_marks' => 100,
                'pass_marks' => 35,
                'is_active' => true,
            ],
            [
                'name' => 'Quran',
                'code' => 'QUR',
                'type' => 'practical',
                'total_marks' => 50,
                'pass_marks' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Islamic Studies',
                'code' => 'ISL',
                'type' => 'theory',
                'total_marks' => 100,
                'pass_marks' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'General Science',
                'code' => 'SCI',
                'type' => 'theory',
                'total_marks' => 100,
                'pass_marks' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'Bengali',
                'code' => 'BEN',
                'type' => 'theory',
                'total_marks' => 100,
                'pass_marks' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'Hifz Practice',
                'code' => 'HIFZ',
                'type' => 'practical',
                'total_marks' => 50,
                'pass_marks' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
