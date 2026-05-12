<?php

namespace App\Livewire\Parent;

use App\Models\AttendanceRecord;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class ChildDetail extends Component
{
    use WithPagination;

    public Student $student;
    public ?string $start_date = null;
    public ?string $end_date = null;

    public function mount(Student $student): void
    {
        $this->authorize('view', $student);
        $this->student = $student->load('classRoom');
        $this->start_date = now()->subDays(14)->toDateString();
        $this->end_date = now()->toDateString();
    }

    public function render()
    {
        return view('livewire.parent.child-detail', [
            'records' => AttendanceRecord::query()
                ->where('student_id', $this->student->id)
                ->whereBetween('date', [$this->start_date, $this->end_date])
                ->latest('date')
                ->paginate(15),
        ])->layout('components.app-layout', ['title' => 'Detail Anak']);
    }
}
