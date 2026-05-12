<?php

namespace App\Livewire\Admin;

use App\Models\Holiday;
use Livewire\Component;

class Holidays extends Component
{
    public string $title = '';
    public string $date = '';
    public bool $is_recurring = false;

    protected array $rules = [
        'title' => ['required', 'string', 'max:255'],
        'date' => ['required', 'date'],
        'is_recurring' => ['boolean'],
    ];

    public function save(): void
    {
        Holiday::query()->create($this->validate());
        $this->reset(['title', 'date', 'is_recurring']);
        $this->dispatch('toast', message: 'Hari libur berhasil disimpan.');
    }

    public function delete(Holiday $holiday): void
    {
        $holiday->delete();
        $this->dispatch('toast', message: 'Hari libur berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.holidays', [
            'holidays' => Holiday::query()->latest('date')->get(),
        ])->layout('components.app-layout', ['title' => 'Hari Libur']);
    }
}
