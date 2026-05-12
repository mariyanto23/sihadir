<?php

namespace App\Livewire\Admin\Students;

use App\Models\Student;
use Livewire\Component;

class Show extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $this->student = $student->load(['classRoom', 'faceDescriptors', 'attendanceRecords' => fn ($query) => $query->latest('date')->take(10)]);
    }

    public function render()
    {
        return view('livewire.admin.students.show')
            ->layout('components.app-layout', ['title' => 'Detail Siswa']);
    }
}
