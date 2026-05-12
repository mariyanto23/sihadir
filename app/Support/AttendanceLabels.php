<?php

namespace App\Support;

final class AttendanceLabels
{
    public static function badge(string $status): string
    {
        return match ($status) {
            'Hadir', 'approved' => 'bg-green-100 text-green-700 ring-green-200',
            'Terlambat', 'pending' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
            'Izin' => 'bg-blue-100 text-blue-700 ring-blue-200',
            'Sakit' => 'bg-purple-100 text-purple-700 ring-purple-200',
            'Tidak Hadir', 'rejected' => 'bg-red-100 text-red-700 ring-red-200',
            default => 'bg-slate-100 text-slate-700 ring-slate-200',
        };
    }
}
