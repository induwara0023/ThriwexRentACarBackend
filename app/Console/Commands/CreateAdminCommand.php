<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a default admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'admin@thriwex.com';
        $password = 'admin123';

        $user = User::where('email', $email)->first();

        if ($user) {
            $this->info("Admin already exists with email: $email");
            return;
        }

        User::create([
            'name' => 'System Admin',
            'email' => $email,
            'password' => $password,
        ]);

        $this->info("Admin created successfully!");
        $this->info("Email: $email");
        $this->info("Password: $password");
    }
}
