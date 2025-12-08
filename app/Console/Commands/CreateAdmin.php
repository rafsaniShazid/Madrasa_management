<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email= : Admin email address} {--name= : Admin name} {--password= : Admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the Madrasa Management System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating Admin User for Madrasa Management System');
        $this->line('');

        // Get admin details
        $name = $this->option('name') ?: $this->ask('Admin name', 'Admin');
        $email = $this->option('email') ?: $this->ask('Admin email', 'admin@madrasa.com');
        
        // Validate email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            if (User::where('email', $email)->exists()) {
                if ($this->confirm("User with email {$email} already exists. Reset password?")) {
                    return $this->resetPassword($email);
                }
                $this->error('Admin creation cancelled.');
                return 1;
            }
            $this->error('Invalid email address.');
            return 1;
        }

        // Get password
        $password = $this->option('password') ?: $this->secret('Admin password (min 8 characters)');
        
        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return 1;
        }

        // Create admin user
        try {
            $admin = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $this->info('✅ Admin user created successfully!');
            $this->line('');
            $this->line("📧 Email: {$admin->email}");
            $this->line("🔑 Password: [hidden for security]");
            $this->line('');
            $this->line("🌐 Login at: " . url('/admin'));
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to create admin user: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Reset password for existing admin
     */
    private function resetPassword(string $email): int
    {
        $password = $this->secret('Enter new password (min 8 characters)');
        
        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return 1;
        }

        try {
            $user = User::where('email', $email)->first();
            $user->update(['password' => Hash::make($password)]);
            
            $this->info('✅ Admin password reset successfully!');
            $this->line('');
            $this->line("📧 Email: {$user->email}");
            $this->line("🔑 Password: [updated]");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to reset password: ' . $e->getMessage());
            return 1;
        }
    }
}
