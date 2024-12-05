<?php

namespace App\Enums;

enum Departments: int
{

    case IT = 1;
    case Administracion = 2;
    case IDi = 3;
    case Food = 4;
    case ESG = 5;
    case Calidad = 6;

    public function label(): string
    {
        return match($this) {
            self::IT => 'IT',
            self::Administracion => 'Adminisración',
            self::IDi => 'IDi',
            self::Food => 'Food',
            self::ESG => 'ESG',
            self::Calidad => 'Calidad',
        };
    }

}
