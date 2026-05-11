<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'admintu123';

        $users = [
            ['name' => 'Admin TU 1', 'email' => 'admintu@gmail.com'],
            ['name' => 'Admin TU 2', 'email' => 'admintu2@gmail.com'],
            ['name' => 'Admin TU 3', 'email' => 'admintu3@gmail.com'],
            ['name' => 'Admin TU 4', 'email' => 'admintu4@gmail.com'],
            ['name' => 'Admin TU 5', 'email' => 'admintu5@gmail.com'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => Hash::make($password)]
            );
        }
    }
}

