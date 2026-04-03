<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case BUYER = 'buyer';
    case DELIVERY = 'delivery';

    // Un método extra para mostrar nombres bonitos en la interfaz
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::BUYER => 'Cliente',
            self::DELIVERY => 'Repartidor',
        };
    }
}