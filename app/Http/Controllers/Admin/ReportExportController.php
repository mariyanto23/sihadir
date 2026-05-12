<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportExportController extends Controller
{
    public function excel()
    {
        return Excel::download(new AttendanceExport(request()->query()), 'laporan-presensi.xlsx');
    }

    public function pdf(ReportService $reportService)
    {
        $filters = $reportService->normalizeFilters(request()->query());

        return Pdf::loadView('exports.attendance-pdf', [
            'records' => $reportService->rows($filters),
            'filters' => $filters,
        ])->download('laporan-presensi.pdf');
    }
}
