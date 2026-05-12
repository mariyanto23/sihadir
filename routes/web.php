<?php

use App\Http\Controllers\Admin\FaceDescriptorController;
use App\Http\Controllers\Admin\ReportExportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Storage\StudentPhotoController;
use App\Http\Controllers\Student\FaceAttendanceController;
use App\Livewire\Admin\Classes\Index as ClassIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Holidays;
use App\Livewire\Admin\LeaveRequests as AdminLeaveRequests;
use App\Livewire\Admin\Reports;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Students\Index as StudentIndex;
use App\Livewire\Admin\Students\Show as StudentShow;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Parent\ChildDetail;
use App\Livewire\Parent\Children;
use App\Livewire\Parent\Dashboard as ParentDashboard;
use App\Livewire\Parent\Profile as ParentProfile;
use App\Livewire\Student\Dashboard as StudentDashboard;
use App\Livewire\Student\FaceScanner;
use App\Livewire\Student\History;
use App\Livewire\Student\LeaveRequests as StudentLeaveRequests;
use App\Livewire\Student\Profile as StudentProfile;
use App\Support\Role;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/student-photos/{student}', [StudentPhotoController::class, 'show'])->middleware('auth')->name('students.photo');

Route::middleware(['auth', 'role:'.Role::ADMIN])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/students', StudentIndex::class)->name('students.index');
    Route::get('/students/{student}', StudentShow::class)->name('students.show');
    Route::post('/students/{student}/face-descriptors', [FaceDescriptorController::class, 'store'])->name('students.face-descriptors.store');
    Route::delete('/students/{student}/face-descriptors/{descriptor}', [FaceDescriptorController::class, 'destroy'])->name('students.face-descriptors.destroy');
    Route::get('/classes', ClassIndex::class)->name('classes.index');
    Route::get('/accounts', UserManagement::class)->name('accounts.index');
    Route::get('/leave-requests', AdminLeaveRequests::class)->name('leave-requests.index');
    Route::get('/holidays', Holidays::class)->name('holidays.index');
    Route::get('/settings', Settings::class)->name('settings.index');
    Route::get('/reports', Reports::class)->name('reports.index');
    Route::get('/reports/export-excel', [ReportExportController::class, 'excel'])->name('reports.export-excel');
    Route::get('/reports/export-pdf', [ReportExportController::class, 'pdf'])->name('reports.export-pdf');
});

Route::middleware(['auth', 'role:'.Role::STUDENT])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    Route::get('/scan', FaceScanner::class)->name('scan');
    Route::get('/history', History::class)->name('history');
    Route::get('/leave-requests', StudentLeaveRequests::class)->name('leave-requests.index');
    Route::get('/profile', StudentProfile::class)->name('profile');
    Route::get('/face-descriptors', [FaceAttendanceController::class, 'descriptors'])->name('face-descriptors');
    Route::post('/attendance/face-check-in', [FaceAttendanceController::class, 'store'])->name('attendance.face-check-in');
});

Route::middleware(['auth', 'role:'.Role::PARENT])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', ParentDashboard::class)->name('dashboard');
    Route::get('/children', Children::class)->name('children.index');
    Route::get('/children/{student}', ChildDetail::class)->name('children.show');
    Route::get('/children/{student}/attendance', ChildDetail::class)->name('children.attendance');
    Route::get('/profile', ParentProfile::class)->name('profile');
});
