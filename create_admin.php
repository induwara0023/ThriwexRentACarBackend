<?php
// Script to create an admin user
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@thriwex.com';
$password = 'admin123';

$user = User::where('email', $email)->first();
if (!$user) {
    User::create([
        'name' => 'System Admin',
        'email' => $email,
        'password' => Hash::make($password)
    ]);
    echo "Admin user created successfully.\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} else {
    echo "Admin user already exists with email: $email\n";
}
