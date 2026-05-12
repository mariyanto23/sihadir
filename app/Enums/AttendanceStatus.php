<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Present = 'Hadir';
    case Late = 'Terlambat';
    case Permit = 'Izin';
    case Sick = 'Sakit';
    case Absent = 'Tidak Hadir';
}
