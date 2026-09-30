<?php

namespace Database\Seeders; 

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Account Super Admin
        User::create([
            'name' => 'Super Admin Shopin',
            'email' => 'superadmin@shopin.test',
            'password' => Hash::make('super123'),
            'role' => 'super_admin',
        ]);

        // 2. Buat Account Admin Toko
        User::create([
            'name' => 'Admin Shopin',
            'email' => 'admin@shopin.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 3. Buat Account Pembeli / User biasa
        User::create([
            'name' => 'User Buyer',
            'email' => 'user@shopin.test',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}