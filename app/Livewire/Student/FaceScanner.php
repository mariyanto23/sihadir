<?php

namespace App\Livewire\Student;

use App\Models\AttendanceRecord;
use App\Services\HolidayService;
use Livewire\Component;

class FaceScanner extends Component
{
    public function render(HolidayService $holidayService)
    {
        $student = auth()->user()->student?->load('classRoom');

        return view('livewire.student.face-scanner', [
            'student' => $student,
            'todayRecord' => $student ? AttendanceRecord::query()->where('student_id', $student->id)->whereDate('date', today())->first() : null,
            'holiday' => $holidayService->holidayFor(today()),
        ])->layout('components.app-layout', ['title' => 'Scan Wajah']);
    }
}
