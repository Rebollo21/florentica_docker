<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

// El nombre de la clase DEBE coincidir con el nombre del archivo
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Oswaldo CEO',
            'email' => 'admin@florentica.com',
            'password' => Hash::make('Admin1234*'),
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
        ]);
    }
}
#admin@florentica.com
#Admin1234*
#oswaldorebollo2121@gmail.com
#Rebollo21,