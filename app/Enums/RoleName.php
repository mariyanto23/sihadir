<?php

namespace App\Enums;

enum RoleName: string
{
    case Admin = 'admin';
    case Student = 'student';
    case Parent = 'parent';
}
