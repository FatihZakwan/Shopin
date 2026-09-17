<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Account Admin
        User::create([
            'name' => 'Admin Shopin',
            'email' => 'admin@shopin.test',
            'password' => Hash::make('admin123'),
            'role' => 'ADMIN',
        ]);

        // 2. Buat Account Pembeli
        User::create([
            'name' => 'User Buyer',
            'email' => 'user@shopin.test',
            'password' => Hash::make('user123'),
            'role' => 'USER',
        ]);

        // 3. Buat Sampel Produk dengan Gambar URL Unsplash
        Product::create([
            'name' => 'Sepatu Sneakers Casual White',
            'price' => 250000,
            'category' => 'Fashion',
            'stock' => 10,
            'description' => 'Sepatu sneakers casual nyaman digunakan sehari-hari dengan bahan sintetis berkualitas.',
            'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
        ]);

        Product::create([
            'name' => 'Headphone Bluetooth Bass Boost',
            'price' => 180000,
            'category' => 'Elektronik',
            'stock' => 15,
            'description' => 'Headphone nirkabel dengan koneksi Bluetooth 5.0 dan suara bass yang jernih & mendalam.',
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80',
        ]);

        Product::create([
            'name' => 'Kemeja Flannel Premium Cotton',
            'price' => 135000,
            'category' => 'Fashion',
            'stock' => 20,
            'description' => 'Kemeja lengan panjang bahan flannel katun halus, cocok untuk acara santai maupun semi-formal.',
            'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&q=80',
        ]);

        Product::create([
            'name' => 'Jam Tangan Minimalis Elegant',
            'price' => 320000,
            'category' => 'Aksesoris',
            'stock' => 8,
            'description' => 'Jam tangan desain minimalis dengan tali kulit asli dan ketahanan air hingga 30m.',
            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
        ]);
    }
}