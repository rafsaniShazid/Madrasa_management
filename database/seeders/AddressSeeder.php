<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addresses = [
            // Student 1 (Abdullah Rahman) - Both addresses
            [
                'student_id' => 1,
                'address_type' => 'permanent',
                'village' => 'Char Kukri Mukri',
                'post_office' => 'Bhairab Bazar',
                'thana' => 'Bhairab',
                'district' => 'Kishoreganj',
            ],
            [
                'student_id' => 1,
                'address_type' => 'present',
                'village' => 'Madrasah Campus',
                'post_office' => 'Savar',
                'thana' => 'Savar',
                'district' => 'Dhaka',
            ],
            
            // Student 2 (Muhammad Hassan) - Both addresses
            [
                'student_id' => 2,
                'address_type' => 'permanent',
                'village' => 'Pakundia Sadar',
                'post_office' => 'Pakundia',
                'thana' => 'Pakundia',
                'district' => 'Kishoreganj',
            ],
            [
                'student_id' => 2,
                'address_type' => 'present',
                'village' => 'Madrasah Campus',
                'post_office' => 'Savar',
                'thana' => 'Savar',
                'district' => 'Dhaka',
            ],
            
            // Student 3 (Aisha Khatun) - Only permanent (non-resident)
            [
                'student_id' => 3,
                'address_type' => 'permanent',
                'village' => 'Mithamoin Sadar',
                'post_office' => 'Mithamoin',
                'thana' => 'Mithamoin',
                'district' => 'Kishoreganj',
            ],
            
            // Student 4 (Ibrahim Ali) - Both addresses
            [
                'student_id' => 4,
                'address_type' => 'permanent',
                'village' => 'Karimganj',
                'post_office' => 'Karimganj',
                'thana' => 'Karimganj',
                'district' => 'Kishoreganj',
            ],
            [
                'student_id' => 4,
                'address_type' => 'present',
                'village' => 'Madrasah Campus',
                'post_office' => 'Savar',
                'thana' => 'Savar',
                'district' => 'Dhaka',
            ],
            
            // Student 5 (Hafsa Rahman) - Only permanent (non-resident)
            [
                'student_id' => 5,
                'address_type' => 'permanent',
                'village' => 'Dhanmondi',
                'post_office' => 'Dhanmondi',
                'thana' => 'Dhanmondi',
                'district' => 'Dhaka',
            ],
        ];

        foreach ($addresses as $address) {
            Address::create($address);
        }
    }
}
