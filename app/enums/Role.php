<?php

namespace App\Enums;

enum Role: int
{
    case Admin = 1; // Has access to all the application
    case Management = 2; // Has acces to all the areas and can manage users
    case Supervisor = 3; // Can check clockins and manage leaves requests
    case User = 4;

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Management => 'Dirección',
            self::Supervisor => 'Supervisor',
            self::User => 'Usuario',
        };
    }
}
