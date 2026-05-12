<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Collection;

class SettingService
{
    public function all(): Collection
    {
        return AppSetting::query()->pluck('value', 'key');
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return AppSetting::query()->where('key', $key)->first()?->value ?? $default;
    }

    public function set(string $key, mixed $value): AppSetting
    {
        return AppSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function schoolName(): string
    {
        return (string) $this->get('school_name', 'SD N 01 Jatipurwo');
    }

    public function cutoffTime(): string
    {
        $value = $this->get('attendance_cutoff_time', '06:30');

        return is_array($value) ? ($value['time'] ?? '06:30') : (string) $value;
    }

    public function faceThreshold(): float
    {
        return (float) $this->get('face_threshold', 0.55);
    }

    public function schoolDaysMode(): int
    {
        return (int) $this->get('school_days_mode', 6);
    }
}
