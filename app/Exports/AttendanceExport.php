<?php

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceExport implements FromView
{
    public function __construct(private readonly array $filters)
    {
    }

    public function view(): View
    {
        return view('exports.attendance-excel', [
            'records' => app(ReportService::class)->rows($this->filters),
            'filters' => app(ReportService::class)->normalizeFilters($this->filters),
        ]);
    }
}
