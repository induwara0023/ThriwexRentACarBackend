<?php
// Script to create an admin user
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@thriwex2.com')->first();
if (!$user) {
    User::create([
        'name' => 'System Admin',
        'email' => 'admin@thriwex2.com',
        'password' => Hash::make('password')
    ]);
    echo "Admin user created successfully.\n";
} else {
    echo "Admin user already exists.\n";
}
