<?php

namespace App\Policies;

use App\Models\AttendanceRecord;
use App\Models\User;
use App\Support\Role;

class AttendanceRecordPolicy
{
    public function view(User $user, AttendanceRecord $attendanceRecord): bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $attendanceRecord->student->user_id === $user->id;
        }

        if ($user->hasRole(Role::PARENT)) {
            return $user->children()->whereKey($attendanceRecord->student_id)->exists();
        }

        return false;
    }
}
