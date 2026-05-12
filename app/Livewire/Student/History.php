<?php

namespace App\Livewire\Student;

use App\Models\AttendanceRecord;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public function render()
    {
        $student = auth()->user()->student;

        return view('livewire.student.history', [
            'records' => $student
                ? AttendanceRecord::query()->where('student_id', $student->id)->latest('date')->paginate(15)
                : collect(),
        ])->layout('components.app-layout', ['title' => 'Riwayat Presensi']);
    }
}
