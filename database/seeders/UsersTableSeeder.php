<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea l'utente admin solo se non esiste già
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}