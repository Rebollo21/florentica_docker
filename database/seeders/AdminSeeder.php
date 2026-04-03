<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@florentica.com'], // Busca este correo
            [
                'name' => 'Lic. Rebollo',
                'password' => Hash::make('admin123'), // Tu nueva clave temporal
                'role' => 'admin', 
            ]
        );
    }
}