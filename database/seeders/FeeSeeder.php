<?php

namespace Database\Seeders;

use App\Models\Fee;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fees = [
            [
                'student_id' => 1,
                'admit_form_fee' => 100.00,
                'id_card' => 150.00,
                'admission_fee' => 2000.00,
            ],
            [
                'student_id' => 2,
                'admit_form_fee' => 100.00,
                'id_card' => 150.00,
                'admission_fee' => 1800.00, // Returning student discount
            ],
            [
                'student_id' => 3,
                'admit_form_fee' => 100.00,
                'id_card' => 150.00,
                'admission_fee' => 2200.00, // Female section additional
            ],
            [
                'student_id' => 4,
                'admit_form_fee' => 100.00,
                'id_card' => 150.00,
                'admission_fee' => 1500.00, // Advanced level reduced fee
            ],
            [
                'student_id' => 5,
                'admit_form_fee' => 100.00,
                'id_card' => 150.00,
                'admission_fee' => 2200.00, // Female section additional
            ],
        ];

        foreach ($fees as $fee) {
            Fee::create($fee);
        }
    }
}
