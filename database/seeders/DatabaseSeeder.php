<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun admin
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpus.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Buat akun user
        User::create([
            'name' => 'User Demo',
            'email' => 'user@perpus.test',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
