<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes; // <-- 1. IMPORTANTE: Importar el manual
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable implements MustVerifyEmail
{
    // <-- 2. IMPORTANTE: Usar el manual aquí dentro
    use HasFactory, Notifiable, SoftDeletes; 

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'es_premium',
    ];

    /**
     * Atributos que deben ocultarse en respuestas JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Definición de Casts.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'deleted_at' => 'datetime', // <-- 3. Sugerencia: Castear la fecha de borrado
        ];
    }

    public function payments()
{
    return $this->hasMany(Payment::class);
}

}