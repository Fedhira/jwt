<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'Admin', // Sesuai field di tabel `users`
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // Sesuai enum di tabel `users`
            'kategori_id' => null, // Jika tidak ada kategori untuk admin
        ]);
    }
}
