<?php

namespace App\Livewire\Admin;

use App\Models\ClassRoom;
use App\Services\ReportService;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?int $class_id = null;
    public ?string $status = null;

    public function mount(): void
    {
        $this->start_date = now()->startOfMonth()->toDateString();
        $this->end_date = now()->toDateString();
    }

    public function filters(): array
    {
        return [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'class_id' => $this->class_id,
            'status' => $this->status,
        ];
    }

    public function render(ReportService $reportService)
    {
        return view('livewire.admin.reports', [
            'records' => $reportService->paginate($this->filters()),
            'recap' => $reportService->recap($this->filters()),
            'classes' => ClassRoom::query()->orderBy('level')->orderBy('name')->get(),
        ])->layout('components.app-layout', ['title' => 'Laporan']);
    }
}
