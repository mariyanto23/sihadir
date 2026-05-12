<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\CarbonInterface;

class HolidayService
{
    public function holidayFor(CarbonInterface $date): ?Holiday
    {
        return Holiday::query()
            ->whereDate('date', $date->toDateString())
            ->orWhere(function ($query) use ($date) {
                $query->where('is_recurring', true)
                    ->whereMonth('date', $date->month)
                    ->whereDay('date', $date->day);
            })
            ->first();
    }

    public function isHoliday(CarbonInterface $date): bool
    {
        return $this->holidayFor($date) !== null;
    }

    public function isSchoolDay(CarbonInterface $date): bool
    {
        $mode = app(SettingService::class)->schoolDaysMode();
        $dayOfWeek = (int) $date->isoWeekday();

        return $mode === 5 ? $dayOfWeek <= 5 : $dayOfWeek <= 6;
    }
}
