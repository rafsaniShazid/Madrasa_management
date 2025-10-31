<?php

namespace App\Console\Commands;

use App\Models\Meal;
use App\Models\Student;
use Illuminate\Console\Command;

class CreateDailyMeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meals:create-daily {--date= : The date for meal creation (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily meal records for all active students with default attendance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? $this->option('date') : now()->format('Y-m-d');

        $this->info("Creating daily meal records for date: {$date}");

        // Get all active students
        $students = Student::where('is_active', true)->get();

        if ($students->isEmpty()) {
            $this->warn('No active students found!');
            return;
        }

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($students as $student) {
            // Check if meal record already exists for this student and date
            $existingMeal = Meal::where('student_id', $student->student_id)
                                ->where('meal_date', $date)
                                ->first();

            if ($existingMeal) {
                $skippedCount++;
                continue;
            }

            // Create new meal record with all meals defaulting to true (attended)
            Meal::create([
                'student_id' => $student->student_id,
                'class_id' => $student->class_id,
                'meal_date' => $date,
                'breakfast' => true,
                'lunch' => true,
                'dinner' => true,
            ]);

            $createdCount++;
        }

        $this->info("✅ Created {$createdCount} new meal records");
        if ($skippedCount > 0) {
            $this->info("⏭️  Skipped {$skippedCount} existing records");
        }

        $this->info("📊 Total students processed: " . $students->count());
    }
}
