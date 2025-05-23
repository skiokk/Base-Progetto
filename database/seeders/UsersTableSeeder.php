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
        
        // Creiamo alcuni utenti di esempio
        $users = [
            [
                'name' => 'Mario Rossi',
                'email' => 'mario.rossi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Giulia Bianchi',
                'email' => 'giulia.bianchi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Marco Verdi',
                'email' => 'marco.verdi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Laura Neri',
                'email' => 'laura.neri@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Paolo Ferrari',
                'email' => 'paolo.ferrari@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Francesca Ricci',
                'email' => 'francesca.ricci@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Luca Marino',
                'email' => 'luca.marino@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Valentina Greco',
                'email' => 'valentina.greco@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Simone Russo',
                'email' => 'simone.russo@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Michela Romano',
                'email' => 'michela.romano@example.com',
                'password' => Hash::make('password'),
            ],
        ];
        
        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create($userData);
            }
        }
    }
}