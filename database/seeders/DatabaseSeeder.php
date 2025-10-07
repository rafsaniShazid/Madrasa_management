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
        $this->call([
            StudentSeeder::class,
            AddressSeeder::class,
            FeeSeeder::class,
            SubjectSeeder::class,
            ClassSubjectSeeder::class,
            ExamSeeder::class,
            ResultSeeder::class,
        ]);

        // Create admin user for Filament
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@madrasa.com',
        ]);
    }
}
