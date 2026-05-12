<?php

namespace App\Livewire\Student;

use App\Models\LeaveRequest;
use Livewire\Component;

class LeaveRequests extends Component
{
    public string $type = 'Izin';
    public string $start_date = '';
    public string $end_date = '';
    public string $reason = '';

    public function submit(): void
    {
        $data = $this->validate([
            'type' => ['required', 'in:Izin,Sakit'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $student = auth()->user()->student;
        abort_if(! $student, 403);

        $student->leaveRequests()->create($data);
        $this->reset(['start_date', 'end_date', 'reason']);
        $this->type = 'Izin';
        $this->dispatch('toast', message: 'Pengajuan berhasil dikirim.');
    }

    public function render()
    {
        $student = auth()->user()->student;

        return view('livewire.student.leave-requests', [
            'requests' => $student ? LeaveRequest::query()->where('student_id', $student->id)->latest()->get() : collect(),
        ])->layout('components.app-layout', ['title' => 'Izin dan Sakit']);
    }
}
