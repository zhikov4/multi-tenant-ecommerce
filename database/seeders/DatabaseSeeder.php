<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat satu akun admin statis agar gampang untuk testing login
        User::create([
            'name' => 'Admin Enterprise',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);
    }
}