<?php

namespace Database\Seeders;

use App\Models\MealRate;
use Illuminate\Database\Seeder;

class MealRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mealRates = [
            [
                'meal_type' => 'breakfast',
                'rate' => 15.00,
                'effective_from' => '2025-01-01',
            ],
            [
                'meal_type' => 'lunch',
                'rate' => 25.00,
                'effective_from' => '2025-01-01',
            ],
            [
                'meal_type' => 'dinner',
                'rate' => 20.00,
                'effective_from' => '2025-01-01',
            ],
        ];

        foreach ($mealRates as $mealRate) {
            MealRate::create($mealRate);
        }
    }
}