<?php

namespace Database\Seeders;

use App\User\Entities\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créez deux utilisateurs
        User::create([
            'id' => Str::uuid(),
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'), // Assurez-vous de hacher le mot de passe
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'), // Hachage du mot de passe
        ]);
    }
}
