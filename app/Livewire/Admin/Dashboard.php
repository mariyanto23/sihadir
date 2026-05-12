<?php

namespace App\Livewire\Admin;

use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Services\HolidayService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(HolidayService $holidayService)
    {
        $today = today();
        $records = AttendanceRecord::query()->whereDate('date', $today);

        return view('livewire.admin.dashboard', [
            'totalStudents' => Student::query()->count(),
            'stats' => [
                'Hadir' => (clone $records)->where('status', 'Hadir')->count(),
                'Terlambat' => (clone $records)->where('status', 'Terlambat')->count(),
                'Izin' => (clone $records)->where('status', 'Izin')->count(),
                'Sakit' => (clone $records)->where('status', 'Sakit')->count(),
                'Tidak Hadir' => (clone $records)->where('status', 'Tidak Hadir')->count(),
            ],
            'recentRecords' => AttendanceRecord::query()->with('student.classRoom')->latest()->take(8)->get(),
            'holiday' => $holidayService->holidayFor($today),
        ])->layout('components.app-layout', ['title' => 'Dashboard Admin']);
    }
}
