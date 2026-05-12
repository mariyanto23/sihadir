<?php

namespace App\Providers;

use App\Models\AttendanceRecord;
use App\Models\LeaveRequest;
use App\Models\Student;
use App\Policies\AttendanceRecordPolicy;
use App\Policies\LeaveRequestPolicy;
use App\Policies\StudentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(AttendanceRecord::class, AttendanceRecordPolicy::class);
        Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);
    }
}
