<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat User Admin
        User::create([
            'name' => 'Admin Shopin',
            'email' => 'admin@shopin.test',
            'password' => 'admin123',
            'role' => 'ADMIN',
        ]);

        // Buat User Pembeli
        User::create([
            'name' => 'User Buyer',
            'email' => 'user@shopin.test',
            'password' => 'user123',
            'role' => 'USER',
        ]);

        // Buat Sample Produk
        Product::create([
            'name' => 'Sepatu Sneakers Casual',
            'price' => 250000,
            'category' => 'Fashion',
            'stock' => 10,
            'description' => 'Sepatu sneakers nyaman untuk harian.',
        ]);

        Product::create([
            'name' => 'Headphone Bluetooth Bass',
            'price' => 180000,
            'category' => 'Elektronik',
            'stock' => 15,
            'description' => 'Headphone suara jernih dan bass mantap.',
        ]);
    }
}