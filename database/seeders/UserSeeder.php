<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersFile = base_path('usersDummy.txt');
        if (!file_exists($usersFile)) {
            $this->command->error("usersDummy.txt file not found at: {$usersFile}");
            return;
        }

        $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (empty($lines)) {
            $this->command->warn("usersDummy.txt is empty or could not be read.");
            return;
        }

        foreach ($lines as $line) {
            $userData = [];
            $pairs = explode('|', $line);
            foreach ($pairs as $pair) {
                list($key, $value) = explode(':', $pair, 2);
                $userData[$key] = $value;
            }

            if (empty($userData['email']) || empty($userData['password']) || empty($userData['first_name']) || empty($userData['last_name'])) {
                $this->command->warn("Skipping malformed line: {$line}");
                continue;
            }

            $firstName = $userData['first_name'];
            $lastName = $userData['last_name'];
            $extension = $userData['extension'] ?? null;
            $email = $userData['email'];
            $password = $userData['password'];

            $fullName = $firstName . ' ' . $lastName . ($extension ? ' ' . $extension : '');

            $this->command->info("Processing user: {$fullName}");

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'extension_name' => $extension,
                    'password' => Hash::make($password),
                    'is_admin' => false,
                ]
            );

            if ($user->wasRecentlyCreated) {
                $this->command->info(" -> Created: {$email}");
            } else {
                $this->command->warn(" -> Skipped (already exists): {$email}");
            }
        }
        $this->command->info('User seeding completed.');
    }
}
