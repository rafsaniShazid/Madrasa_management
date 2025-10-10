<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['name' => 'Play'],
            ['name' => 'Nursery'],
            ['name' => 'First'],
            ['name' => 'Second'],
            ['name' => 'Third'],
            ['name' => 'Fourth'],
            ['name' => 'Nazira'],
            ['name' => 'Hifzul Quran'],
        ];

        foreach ($classes as $class) {
            SchoolClass::create($class);
        }
    }
}
