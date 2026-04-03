<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    User::create([
        'name' => 'Oswaldo CEO',
        'email' => 'admin@florentica.com', // O el correo que usted prefiera
        'password' => Hash::make('Admin1234*'), // Use una contraseña fuerte
        'role' => UserRole::ADMIN,
        'email_verified_at' => now(),
    ]);
}
}
