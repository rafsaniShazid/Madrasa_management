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
            // Play class (ID: 1) - Basic subjects
            ['class_id' => 1, 'subject_id' => $subjects['ENG']->id],
            ['class_id' => 1, 'subject_id' => $subjects['BEN']->id],
            ['class_id' => 1, 'subject_id' => $subjects['QUR']->id],
            
            // Nursery class (ID: 2) - Basic + Math
            ['class_id' => 2, 'subject_id' => $subjects['MATH']->id],
            ['class_id' => 2, 'subject_id' => $subjects['ENG']->id],
            ['class_id' => 2, 'subject_id' => $subjects['BEN']->id],
            ['class_id' => 2, 'subject_id' => $subjects['QUR']->id],
            
            // First class (ID: 3) - Core subjects
            ['class_id' => 3, 'subject_id' => $subjects['MATH']->id],
            ['class_id' => 3, 'subject_id' => $subjects['ENG']->id],
            ['class_id' => 3, 'subject_id' => $subjects['BEN']->id],
            ['class_id' => 3, 'subject_id' => $subjects['ARA']->id],
            ['class_id' => 3, 'subject_id' => $subjects['QUR']->id],
            ['class_id' => 3, 'subject_id' => $subjects['ISL']->id],
            
            // Second class (ID: 4) - All subjects except science
            ['class_id' => 4, 'subject_id' => $subjects['MATH']->id],
            ['class_id' => 4, 'subject_id' => $subjects['ENG']->id],
            ['class_id' => 4, 'subject_id' => $subjects['BEN']->id],
            ['class_id' => 4, 'subject_id' => $subjects['ARA']->id],
            ['class_id' => 4, 'subject_id' => $subjects['QUR']->id],
            ['class_id' => 4, 'subject_id' => $subjects['ISL']->id],
            
            // Third class (ID: 5) - All academic subjects
            ['class_id' => 5, 'subject_id' => $subjects['MATH']->id],
            ['class_id' => 5, 'subject_id' => $subjects['ENG']->id],
            ['class_id' => 5, 'subject_id' => $subjects['BEN']->id],
            ['class_id' => 5, 'subject_id' => $subjects['ARA']->id],
            ['class_id' => 5, 'subject_id' => $subjects['QUR']->id],
            ['class_id' => 5, 'subject_id' => $subjects['ISL']->id],
            ['class_id' => 5, 'subject_id' => $subjects['SCI']->id],
            
            // Fourth class (ID: 6) - All academic subjects
            ['class_id' => 6, 'subject_id' => $subjects['MATH']->id],
            ['class_id' => 6, 'subject_id' => $subjects['ENG']->id],
            ['class_id' => 6, 'subject_id' => $subjects['BEN']->id],
            ['class_id' => 6, 'subject_id' => $subjects['ARA']->id],
            ['class_id' => 6, 'subject_id' => $subjects['QUR']->id],
            ['class_id' => 6, 'subject_id' => $subjects['ISL']->id],
            ['class_id' => 6, 'subject_id' => $subjects['SCI']->id],
            
            // Nazira class (ID: 7) - Religious focus
            ['class_id' => 7, 'subject_id' => $subjects['ARA']->id],
            ['class_id' => 7, 'subject_id' => $subjects['QUR']->id],
            ['class_id' => 7, 'subject_id' => $subjects['ISL']->id],
            
            // Hifzul Quran class (ID: 8) - Memorization focus
            ['class_id' => 8, 'subject_id' => $subjects['QUR']->id],
            ['class_id' => 8, 'subject_id' => $subjects['HIFZ']->id],
            ['class_id' => 8, 'subject_id' => $subjects['ISL']->id],
        ];

        foreach ($classSubjects as $classSubject) {
            ClassSubject::create($classSubject + ['is_active' => true]);
        }
    }
}
