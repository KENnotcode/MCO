<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-user {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => $this->argument('email'),
            'password' => \Illuminate\Support\Facades\Hash::make($this->argument('password')),
            'is_admin' => true,
        ]);

        $this->info("Admin user {$user->email} created successfully.");
    }
}
