<?php

namespace App\Services;

use App\Enums\AttendanceMethod;
use App\Enums\AttendanceStatus;
use App\Models\AttendanceRecord;
use App\Models\LeaveRequest;
use App\Models\Student;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        private readonly HolidayService $holidayService,
        private readonly SettingService $settingService,
    ) {
    }

    public function determineStatus(CarbonInterface $time): AttendanceStatus
    {
        $cutoff = Carbon::parse($time->toDateString().' '.$this->settingService->cutoffTime());

        return $time->lessThanOrEqualTo($cutoff)
            ? AttendanceStatus::Present
            : AttendanceStatus::Late;
    }

    public function faceCheckIn(Student $student, int $userId, ?float $distance = null): AttendanceRecord
    {
        $now = now();

        if (! $this->holidayService->isSchoolDay($now) || $this->holidayService->isHoliday($now)) {
            abort(422, 'Hari ini libur, presensi tidak tersedia.');
        }

        if (! $student->has_embedding) {
            abort(422, 'Wajah belum diregistrasi.');
        }

        $status = $this->determineStatus($now);

        return AttendanceRecord::query()->updateOrCreate(
            ['student_id' => $student->id, 'date' => $now->toDateString()],
            [
                'check_in_time' => $now->format('H:i:s'),
                'status' => $status,
                'method' => AttendanceMethod::Face,
                'notes' => $distance !== null ? 'Jarak wajah: '.number_format($distance, 3) : null,
                'created_by' => $userId,
            ],
        );
    }

    public function approveLeave(LeaveRequest $leaveRequest, int $reviewerId, ?string $notes = null): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $reviewerId, $notes) {
            $leaveRequest->update([
                'status' => 'approved',
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
                'admin_notes' => $notes,
            ]);

            $date = $leaveRequest->start_date->copy();
            while ($date->lessThanOrEqualTo($leaveRequest->end_date)) {
                if ($this->holidayService->isSchoolDay($date) && ! $this->holidayService->isHoliday($date)) {
                    AttendanceRecord::query()->updateOrCreate(
                        ['student_id' => $leaveRequest->student_id, 'date' => $date->toDateString()],
                        [
                            'status' => $leaveRequest->type,
                            'method' => AttendanceMethod::Leave,
                            'notes' => $leaveRequest->reason,
                            'created_by' => $reviewerId,
                        ],
                    );
                }
                $date->addDay();
            }

            return $leaveRequest->refresh();
        });
    }

    public function rejectLeave(LeaveRequest $leaveRequest, int $reviewerId, ?string $notes = null): LeaveRequest
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);

        return $leaveRequest->refresh();
    }

    public function markAbsent(CarbonInterface $date): int
    {
        if (! $this->holidayService->isSchoolDay($date) || $this->holidayService->isHoliday($date)) {
            return 0;
        }

        $dateString = $date->toDateString();
        $count = 0;
        Student::query()->select('id')->chunkById(200, function ($students) use ($dateString, &$count) {
            foreach ($students as $student) {
                $exists = AttendanceRecord::query()
                    ->where('student_id', $student->id)
                    ->whereDate('date', $dateString)
                    ->exists();

                if (! $exists) {
                    AttendanceRecord::query()->create([
                        'student_id' => $student->id,
                        'date' => $dateString,
                        'status' => AttendanceStatus::Absent,
                        'method' => AttendanceMethod::Auto,
                    ]);
                    $count++;
                }
            }
        });

        return $count;
    }
}
