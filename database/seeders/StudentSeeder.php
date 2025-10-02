<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'session' => '2025',
                'class' => 'play',
                'student_type' => 'new',
                'gender' => 'male',
                'residence_status' => 'resident',
                'name' => 'Abdullah Rahman',
                'father_name' => 'Mohammad Rahman',
                'mother_name' => 'Fatima Rahman',
                'date_of_birth' => '2010-05-15',
                'nid_birth_no' => '20105623789012',
                'nationality' => 'Bangladeshi',
                'blood_group' => 'B+',
                'guardian_phone' => '01712345678',
                'sms_number' => '01712345678',
            ],
            [
                'session' => '2025',
                'class' => 'nursery',
                'student_type' => 'old',
                'gender' => 'male',
                'residence_status' => 'resident',
                'name' => 'Muhammad Hassan',
                'father_name' => 'Abdul Karim',
                'mother_name' => 'Ayesha Begum',
                'date_of_birth' => '2009-08-22',
                'nid_birth_no' => '20095634123456',
                'nationality' => 'Bangladeshi',
                'blood_group' => 'A+',
                'guardian_phone' => '01823456789',
                'sms_number' => '01823456789',
            ],
            [
                'session' => '2025',
                'class' => 'first',
                'student_type' => 'new',
                'gender' => 'female',
                'residence_status' => 'non-resident',
                'name' => 'Aisha Khatun',
                'father_name' => 'Md. Zakir Hossain',
                'mother_name' => 'Rashida Begum',
                'date_of_birth' => '2008-12-10',
                'nid_birth_no' => '20086745890123',
                'nationality' => 'Bangladeshi',
                'blood_group' => 'O+',
                'guardian_phone' => '01934567890',
                'sms_number' => '01934567890',
            ],
            [
                'session' => '2025',
                'class' => 'nazira',
                'student_type' => 'old',
                'gender' => 'male',
                'residence_status' => 'resident',
                'name' => 'Ibrahim Ali',
                'father_name' => 'Maulana Yusuf Ali',
                'mother_name' => 'Khadija Begum',
                'date_of_birth' => '2007-03-28',
                'nid_birth_no' => '20073456789012',
                'nationality' => 'Bangladeshi',
                'blood_group' => 'AB+',
                'guardian_phone' => '01645678901',
                'sms_number' => '01645678901',
            ],
            [
                'session' => '2025',
                'class' => 'hifzul_quran',
                'student_type' => 'new',
                'gender' => 'female',
                'residence_status' => 'non-resident',
                'name' => 'Hafsa Rahman',
                'father_name' => 'Dr. Aminul Islam',
                'mother_name' => 'Salma Khatun',
                'date_of_birth' => '2011-07-14',
                'nid_birth_no' => '20117890123456',
                'nationality' => 'Bangladeshi',
                'blood_group' => 'B-',
                'guardian_phone' => '01756789012',
                'sms_number' => '01756789012',
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
