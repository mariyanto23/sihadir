<?php

namespace App\Livewire\Admin;

use App\Models\LeaveRequest;
use App\Services\AttendanceService;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveRequests extends Component
{
    use WithPagination;

    public string $status = '';
    public ?string $adminNotes = null;

    public function approve(LeaveRequest $leaveRequest, AttendanceService $attendanceService): void
    {
        $attendanceService->approveLeave($leaveRequest, auth()->id(), $this->adminNotes);
        $this->adminNotes = null;
        $this->dispatch('toast', message: 'Pengajuan berhasil disetujui.');
    }

    public function reject(LeaveRequest $leaveRequest, AttendanceService $attendanceService): void
    {
        $attendanceService->rejectLeave($leaveRequest, auth()->id(), $this->adminNotes);
        $this->adminNotes = null;
        $this->dispatch('toast', message: 'Pengajuan berhasil ditolak.');
    }

    public function render()
    {
        return view('livewire.admin.leave-requests', [
            'requests' => LeaveRequest::query()
                ->with('student.classRoom')
                ->when($this->status, fn ($query) => $query->where('status', $this->status))
                ->latest()
                ->paginate(10),
        ])->layout('components.app-layout', ['title' => 'Izin dan Sakit']);
    }
}
