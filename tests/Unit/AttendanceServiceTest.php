<?php

namespace Tests\Unit;

use App\Models\ClassRoom;
use App\Models\Holiday;
use App\Models\Student;
use App\Services\AttendanceService;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_determine_status_uses_cutoff_time(): void
    {
        app(SettingService::class)->set('attendance_cutoff_time', '06:30');
        $service = app(AttendanceService::class);

        $this->assertSame('Hadir', $service->determineStatus(now()->setTime(6, 30))->value);
        $this->assertSame('Terlambat', $service->determineStatus(now()->setTime(6, 31))->value);
    }

    public function test_mark_absent_does_not_duplicate_records(): void
    {
        app(SettingService::class)->set('school_days_mode', 6);
        $class = ClassRoom::factory()->create(['name' => '3A', 'level' => 3]);
        Student::factory()->count(2)->create(['class_id' => $class->id]);

        $service = app(AttendanceService::class);
        $date = now()->next('Monday');

        $this->assertSame(2, $service->markAbsent($date));
        $this->assertSame(0, $service->markAbsent($date));
        $this->assertDatabaseCount('attendance_records', 2);
    }

    public function test_mark_absent_skips_holidays(): void
    {
        app(SettingService::class)->set('school_days_mode', 6);
        $class = ClassRoom::factory()->create(['name' => '4A', 'level' => 4]);
        Student::factory()->create(['class_id' => $class->id]);
        $date = now()->next('Monday');
        Holiday::query()->create(['title' => 'Libur Tes', 'date' => $date->toDateString()]);

        $this->assertSame(0, app(AttendanceService::class)->markAbsent($date));
        $this->assertDatabaseCount('attendance_records', 0);
    }
}
