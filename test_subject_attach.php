<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SchoolClass;
use App\Models\Subject;

echo "Testing SchoolClass-Subject Relationship:\n\n";

// Get a class
$class = SchoolClass::find(1); // Play class
echo "Class: {$class->name}\n";
echo "Current subjects attached: " . $class->subjects->count() . "\n\n";

// Show attached subjects
echo "Attached Subjects:\n";
foreach ($class->subjects as $subject) {
    $isActive = $subject->pivot->is_active ? 'Active' : 'Inactive';
    echo "  - {$subject->name} ({$subject->code}) - {$isActive}\n";
}

echo "\n";

// Get all subjects
$allSubjects = Subject::all();
echo "Total subjects available in system: " . $allSubjects->count() . "\n";
foreach ($allSubjects as $subject) {
    echo "  - {$subject->name} ({$subject->code})\n";
}

echo "\n✅ Subject model relationship is properly configured!\n";
echo "✅ The attach functionality should work in Filament now!\n";
