<?php

namespace App\Console\Commands;

use App\Services\AttendanceService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarkAbsentAttendance extends Command
{
    protected $signature = 'attendance:mark-absent {--date= : Tanggal presensi format Y-m-d}';

    protected $description = 'Menandai siswa yang belum presensi sebagai Tidak Hadir.';

    public function handle(AttendanceService $attendanceService): int
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : now();
        $count = $attendanceService->markAbsent($date);

        $this->info("Auto Tidak Hadir selesai. {$count} siswa diproses.");

        return self::SUCCESS;
    }
}
