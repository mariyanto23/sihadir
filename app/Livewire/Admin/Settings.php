<?php

namespace App\Livewire\Admin;

use App\Services\SettingService;
use Livewire\Component;

class Settings extends Component
{
    public string $school_name = '';
    public string $attendance_cutoff_time = '06:30';
    public int $school_days_mode = 6;
    public string $theme_color = 'blue';
    public float $face_threshold = 0.55;

    public function mount(SettingService $settings): void
    {
        $this->school_name = (string) $settings->get('school_name', 'SD N 01 Jatipurwo');
        $this->attendance_cutoff_time = (string) $settings->get('attendance_cutoff_time', '06:30');
        $this->school_days_mode = (int) $settings->get('school_days_mode', 6);
        $this->theme_color = (string) $settings->get('theme_color', 'blue');
        $this->face_threshold = (float) $settings->get('face_threshold', 0.55);
    }

    public function save(SettingService $settings): void
    {
        $data = $this->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'attendance_cutoff_time' => ['required', 'date_format:H:i'],
            'school_days_mode' => ['required', 'integer', 'in:5,6'],
            'theme_color' => ['required', 'string', 'max:30'],
            'face_threshold' => ['required', 'numeric', 'between:0.3,0.9'],
        ]);

        foreach ($data as $key => $value) {
            $settings->set($key, $value);
        }

        $this->dispatch('toast', message: 'Pengaturan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('components.app-layout', ['title' => 'Pengaturan']);
    }
}
