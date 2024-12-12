<?php

namespace App\enums;

enum Status: int
{
    case Active = 1; // Has access to all the application
    case Inactive = 0; // Has acces to all the areas and can anage users
}
