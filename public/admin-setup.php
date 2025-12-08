<?php
// admin-setup.php - Upload this to Sevalla public folder temporarily

// Basic security - change this secret!
if (!isset($_GET['secret']) || $_GET['secret'] !== 'madrasa2024setup') {
    die('Access denied');
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->boot();

echo "<h2>🏫 Madrasa Management System - Admin Setup</h2>";

try {
    // Create/update admin user
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'admin@madrasa.com'],
        [
            'name' => 'Admin',
            'password' => bcrypt('admin123456'),
            'email_verified_at' => now(),
        ]
    );
    
    echo "✅ <strong>Admin user created successfully!</strong><br><br>";
    echo "📧 <strong>Email:</strong> admin@madrasa.com<br>";
    echo "🔑 <strong>Password:</strong> admin123456<br><br>";
    echo "🌐 <a href='/admin' style='background: #0066cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login to Admin Panel</a><br><br>";
    
    // Security: Delete this file after use
    echo "⚠️ <em>Remember to delete this file after use for security!</em>";
    
} catch (Exception $e) {
    echo "❌ <strong>Error:</strong> " . $e->getMessage() . "<br>";
    echo "Check your database connection and try again.";
}
?>