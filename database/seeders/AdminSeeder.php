<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@madrasa.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123456'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin user created: admin@madrasa.com');
    }
}
