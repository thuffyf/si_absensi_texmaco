<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = 'admintu123';

        $users = [
            ['name' => 'Admin TU 1', 'email' => 'admintu@gmail.com', 'role' => 'tata_usaha'],
            ['name' => 'Admin TU 2', 'email' => 'admintu2@gmail.com', 'role' => 'tata_usaha'],
            ['name' => 'Admin TU 3', 'email' => 'admintu3@gmail.com', 'role' => 'tata_usaha'],
            ['name' => 'Admin TU 4', 'email' => 'admintu4@gmail.com', 'role' => 'tata_usaha'],
            ['name' => 'Admin TU 5', 'email' => 'admintu5@gmail.com', 'role' => 'tata_usaha'],
            ['name' => 'Guru SITEXA', 'email' => 'guru@gmail.com', 'role' => 'guru', 'password' => 'guru123'],
            ['name' => 'Siswa SITEXA', 'email' => 'siswa@gmail.com', 'role' => 'siswa', 'password' => 'siswa123'],
        ];

        foreach ($users as $u) {
            $password = $u['password'] ?? $adminPassword;
            User::updateOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => Hash::make($password), 'role' => $u['role']]
            );
        }
    }
}

