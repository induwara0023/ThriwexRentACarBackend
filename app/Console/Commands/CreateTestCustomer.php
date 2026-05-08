<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestCustomer extends Command
{
    protected $signature = 'create:test-customer
                            {name? : The name of the customer}
                            {email? : The email of the customer}
                            {password? : The password for the customer}';

    protected $description = 'Create a test customer account for login testing';

    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('Customer name', 'Test Customer');
        $email = $this->argument('email') ?? $this->ask('Email address', 'test@example.com');
        $password = $this->argument('password') ?? $this->secret('Password');

        if (!$password) {
            $password = 'password123';
        }

        $customer = Customer::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'nic_no' => '1234567890V',
                'phone' => '0712345678',
                'address' => 'Test Address',
                'status' => 'active',
            ]
        );

        $this->info("✓ Test customer created successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->line('');
        $this->info('You can now login with these credentials at /login');
    }
}
