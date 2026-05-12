<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Support\Role;

class LeaveRequestPolicy
{
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $leaveRequest->student->user_id === $user->id;
        }

        if ($user->hasRole(Role::PARENT)) {
            return $user->children()->whereKey($leaveRequest->student_id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::STUDENT) && $user->student()->exists();
    }

    public function review(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }
}
