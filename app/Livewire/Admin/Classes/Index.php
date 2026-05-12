<?php

namespace App\Livewire\Admin\Classes;

use App\Models\ClassRoom;
use Livewire\Component;

class Index extends Component
{
    public ?int $editingId = null;
    public string $name = '';
    public ?int $level = null;

    protected array $rules = [
        'name' => ['required', 'string', 'max:20'],
        'level' => ['required', 'integer', 'min:1', 'max:6'],
    ];

    public function save(): void
    {
        $data = $this->validate();
        ClassRoom::query()->updateOrCreate(['id' => $this->editingId], $data);
        $this->reset(['editingId', 'name', 'level']);
        $this->dispatch('toast', message: 'Data kelas berhasil disimpan.');
    }

    public function edit(ClassRoom $classRoom): void
    {
        $this->editingId = $classRoom->id;
        $this->name = $classRoom->name;
        $this->level = $classRoom->level;
    }

    public function delete(ClassRoom $classRoom): void
    {
        if ($classRoom->students()->exists()) {
            $this->dispatch('toast', message: 'Kelas masih digunakan siswa.');
            return;
        }

        $classRoom->delete();
        $this->dispatch('toast', message: 'Data kelas berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.classes.index', [
            'classes' => ClassRoom::query()->withCount('students')->orderBy('level')->orderBy('name')->get(),
        ])->layout('components.app-layout', ['title' => 'Data Kelas']);
    }
}
