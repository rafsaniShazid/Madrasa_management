<?php
// admin-setup.php - Place this in your public folder temporarily

// Basic security check
if (!isset($_GET['secret']) || $_GET['secret'] !== 'your-secret-key-here') {
    die('Access denied');
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->boot();

// Create admin user
try {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@madrasa.com'],
        [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]
    );
    
    echo "✅ Admin user created/updated successfully!<br>";
    echo "📧 Email: admin@madrasa.com<br>";
    echo "🔑 Password: password<br>";
    echo "<br>🌐 <a href='/admin'>Login to Admin Panel</a>";
    
    // Delete this file for security
    unlink(__FILE__);
    echo "<br><br>⚠️ This setup file has been deleted for security.";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>