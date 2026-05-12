<?php

namespace App\Livewire\Student;

use App\Models\AttendanceRecord;
use App\Services\HolidayService;
use App\Services\SettingService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(HolidayService $holidayService, SettingService $settingService)
    {
        $student = auth()->user()->student?->load('classRoom');

        return view('livewire.student.dashboard', [
            'student' => $student,
            'todayRecord' => $student ? AttendanceRecord::query()->where('student_id', $student->id)->whereDate('date', today())->first() : null,
            'holiday' => $holidayService->holidayFor(today()),
            'cutoff' => $settingService->cutoffTime(),
        ])->layout('components.app-layout', ['title' => 'Beranda Siswa']);
    }
}
