<?php

namespace Database\Seeders;

use App\Services\SettingService;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = app(SettingService::class);

        $settings->set('school_name', 'SD N 01 Jatipurwo');
        $settings->set('attendance_cutoff_time', '06:30');
        $settings->set('school_days_mode', 6);
        $settings->set('theme_color', 'blue');
        $settings->set('face_threshold', 0.55);
        $settings->set('pwa_name', 'HadirKu');
    }
}
