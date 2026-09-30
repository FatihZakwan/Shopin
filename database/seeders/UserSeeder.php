<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@shopin.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        // 2. Akun Admin Toko
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@shopin.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 3. Akun User/Pembeli
        User::create([
            'name' => 'User Buyer',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}