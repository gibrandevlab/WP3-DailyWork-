<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data Admin
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@gmail.com', 
            'role' => '2', // SuperAdmin
            'status' => 1,
            'hp' => '0812345678901',
            'password' => bcrypt('P@55word'),
            'foto' => null
        ]);

        // Data Pengguna
        User::create([
            'nama' => '19231584_FathurRahmanRifaldi',
            'email' => '19231584@gmail.com',
            'role' => '1', // Admin
            'status' => 1,
            'hp' => '081234567812',
            'password' => bcrypt('Fathur123'),
            'foto' => null
        ]);

        User::create([
            'nama' => 'Sopian Aji',
            'email' => 'sopian4ji@gmail.com',
            'role' => '0', // Customer
            'status' => 1,
            'hp' => '081234567892',
            'password' => bcrypt('P@55word'),
            'foto' => null
        ]);

        // Data Kategori Makanan
        Kategori::create(['nama_kategori' => 'Brownies']);
        Kategori::create(['nama_kategori' => 'Combro']);
        Kategori::create(['nama_kategori' => 'Dawet']);
        Kategori::create(['nama_kategori' => 'Mochi']);
        Kategori::create(['nama_kategori' => 'Wingko']);
    }
}