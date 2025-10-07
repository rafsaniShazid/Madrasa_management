<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSubject;
use App\Models\Subject;

class ClassSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Get subject IDs
        $subjects = Subject::all()->keyBy('code');
        
        // Define which subjects are taught in which classes
        $classSubjects = [
            // Play class - Basic subjects
            ['class' => 'play', 'subject_id' => $subjects['ENG']->id],
            ['class' => 'play', 'subject_id' => $subjects['BEN']->id],
            ['class' => 'play', 'subject_id' => $subjects['QUR']->id],
            
            // Nursery class - Basic + Math
            ['class' => 'nursery', 'subject_id' => $subjects['MATH']->id],
            ['class' => 'nursery', 'subject_id' => $subjects['ENG']->id],
            ['class' => 'nursery', 'subject_id' => $subjects['BEN']->id],
            ['class' => 'nursery', 'subject_id' => $subjects['QUR']->id],
            
            // First class - Core subjects
            ['class' => 'first', 'subject_id' => $subjects['MATH']->id],
            ['class' => 'first', 'subject_id' => $subjects['ENG']->id],
            ['class' => 'first', 'subject_id' => $subjects['BEN']->id],
            ['class' => 'first', 'subject_id' => $subjects['ARA']->id],
            ['class' => 'first', 'subject_id' => $subjects['QUR']->id],
            ['class' => 'first', 'subject_id' => $subjects['ISL']->id],
            
            // Second class - All subjects except science
            ['class' => 'second', 'subject_id' => $subjects['MATH']->id],
            ['class' => 'second', 'subject_id' => $subjects['ENG']->id],
            ['class' => 'second', 'subject_id' => $subjects['BEN']->id],
            ['class' => 'second', 'subject_id' => $subjects['ARA']->id],
            ['class' => 'second', 'subject_id' => $subjects['QUR']->id],
            ['class' => 'second', 'subject_id' => $subjects['ISL']->id],
            
            // Third class - All academic subjects
            ['class' => 'third', 'subject_id' => $subjects['MATH']->id],
            ['class' => 'third', 'subject_id' => $subjects['ENG']->id],
            ['class' => 'third', 'subject_id' => $subjects['BEN']->id],
            ['class' => 'third', 'subject_id' => $subjects['ARA']->id],
            ['class' => 'third', 'subject_id' => $subjects['QUR']->id],
            ['class' => 'third', 'subject_id' => $subjects['ISL']->id],
            ['class' => 'third', 'subject_id' => $subjects['SCI']->id],
            
            // Fourth class - All academic subjects
            ['class' => 'fourth', 'subject_id' => $subjects['MATH']->id],
            ['class' => 'fourth', 'subject_id' => $subjects['ENG']->id],
            ['class' => 'fourth', 'subject_id' => $subjects['BEN']->id],
            ['class' => 'fourth', 'subject_id' => $subjects['ARA']->id],
            ['class' => 'fourth', 'subject_id' => $subjects['QUR']->id],
            ['class' => 'fourth', 'subject_id' => $subjects['ISL']->id],
            ['class' => 'fourth', 'subject_id' => $subjects['SCI']->id],
            
            // Nazira class - Religious focus
            ['class' => 'nazira', 'subject_id' => $subjects['ARA']->id],
            ['class' => 'nazira', 'subject_id' => $subjects['QUR']->id],
            ['class' => 'nazira', 'subject_id' => $subjects['ISL']->id],
            
            // Hifzul Quran class - Memorization focus
            ['class' => 'hifzul_quran', 'subject_id' => $subjects['QUR']->id],
            ['class' => 'hifzul_quran', 'subject_id' => $subjects['HIFZ']->id],
            ['class' => 'hifzul_quran', 'subject_id' => $subjects['ISL']->id],
        ];

        foreach ($classSubjects as $classSubject) {
            ClassSubject::create($classSubject + ['is_active' => true]);
        }
    }
}
