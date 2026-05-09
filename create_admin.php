<?php
// Script to create an admin user
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

<<<<<<< HEAD
$email = 'admin@thriwex.com';
$password = 'admin123';

$user = User::where('email', $email)->first();
if (!$user) {
    User::create([
        'name' => 'System Admin',
        'email' => $email,
        'password' => Hash::make($password)
=======
$user = User::where('email', 'admin@thriwex2.com')->first();
if (!$user) {
    User::create([
        'name' => 'System Admin',
        'email' => 'admin@thriwex2.com',
        'password' => Hash::make('password')
>>>>>>> a264fdc71b352c669154828b7355f25a092a8f08
    ]);
    echo "Admin user created successfully.\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} else {
    echo "Admin user already exists with email: $email\n";
}
