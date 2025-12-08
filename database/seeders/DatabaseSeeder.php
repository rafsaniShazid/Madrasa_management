<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Always create admin user first (works in all environments)
        $this->call(AdminSeeder::class);

        // Only seed test data in development
        if (app()->environment(['local', 'development'])) {
            $this->call([
                SchoolClassSeeder::class, // Must run first!
                StudentSeeder::class,
                AddressSeeder::class,
                FeeSeeder::class,
                SubjectSeeder::class,
                ClassSubjectSeeder::class,
                ExamSeeder::class,
                ResultSeeder::class,
                MealRateSeeder::class,
            ]);
        }
    }
}
