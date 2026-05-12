<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use App\Support\Role;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $student->user_id === $user->id;
        }

        if ($user->hasRole(Role::PARENT)) {
            return $user->children()->whereKey($student->id)->exists();
        }

        return false;
    }

    public function manage(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }
}
