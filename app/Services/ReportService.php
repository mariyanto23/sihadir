<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    public function query(array $filters = []): Builder
    {
        return AttendanceRecord::query()
            ->with(['student.classRoom'])
            ->when($filters['start_date'] ?? null, fn (Builder $query, $date) => $query->whereDate('date', '>=', $date))
            ->when($filters['end_date'] ?? null, fn (Builder $query, $date) => $query->whereDate('date', '<=', $date))
            ->when($filters['class_id'] ?? null, fn (Builder $query, $classId) => $query->whereHas('student', fn (Builder $studentQuery) => $studentQuery->where('class_id', $classId)))
            ->when($filters['status'] ?? null, fn (Builder $query, $status) => $query->where('status', $status))
            ->latest('date');
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query($this->normalizeFilters($filters))->paginate($perPage);
    }

    public function rows(array $filters = []): Collection
    {
        return $this->query($this->normalizeFilters($filters))->get();
    }

    public function recap(array $filters = []): Collection
    {
        return $this->rows($filters)->groupBy(fn ($record) => $record->status->value)->map->count();
    }

    public function normalizeFilters(array $filters): array
    {
        return [
            'start_date' => $filters['start_date'] ?? now()->startOfMonth()->toDateString(),
            'end_date' => $filters['end_date'] ?? Carbon::today()->toDateString(),
            'class_id' => $filters['class_id'] ?? null,
            'status' => $filters['status'] ?? null,
        ];
    }
}
