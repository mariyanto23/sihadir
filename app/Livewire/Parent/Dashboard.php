<?php

namespace App\Livewire\Parent;

use App\Models\AttendanceRecord;
use App\Services\HolidayService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(HolidayService $holidayService)
    {
        $children = auth()->user()->children()->with('classRoom')->get();
        $todayRecords = AttendanceRecord::query()
            ->whereIn('student_id', $children->pluck('id'))
            ->whereDate('date', today())
            ->get()
            ->keyBy('student_id');

        return view('livewire.parent.dashboard', [
            'children' => $children,
            'todayRecords' => $todayRecords,
            'holiday' => $holidayService->holidayFor(today()),
        ])->layout('components.app-layout', ['title' => 'Beranda Orang Tua']);
    }
}
