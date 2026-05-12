<?php

namespace App\Enums;

enum AttendanceMethod: string
{
    case Face = 'face';
    case Manual = 'manual';
    case Leave = 'leave';
    case Auto = 'auto';
}
