<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\FaceCheckInRequest;
use App\Services\AttendanceService;
use App\Services\FaceRecognitionService;
use App\Services\SettingService;

class FaceAttendanceController extends Controller
{
    public function descriptors(FaceRecognitionService $faceRecognitionService, SettingService $settingService)
    {
        $student = request()->user()->student;
        abort_if(! $student, 403);

        return response()->json([
            'descriptors' => $faceRecognitionService->descriptorsFor($student),
            'threshold' => $settingService->faceThreshold(),
        ]);
    }

    public function store(FaceCheckInRequest $request, AttendanceService $attendanceService)
    {
        $student = $request->user()->student;
        abort_if(! $student, 403);

        $record = $attendanceService->faceCheckIn($student, $request->user()->id, $request->float('distance'));

        return response()->json([
            'message' => 'Presensi berhasil dicatat.',
            'status' => $record->status->value,
            'check_in_time' => $record->check_in_time ? substr($record->check_in_time, 0, 5) : null,
        ]);
    }
}
