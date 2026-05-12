<?php

namespace App\Livewire\Admin\Students;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';
    public ?int $filterClass = null;
    public ?int $editingId = null;
    public string $nis = '';
    public string $name = '';
    public ?int $class_id = null;
    public ?string $birth_date = null;
    public ?string $gender = null;
    public $photo = null;
    public bool $showForm = false;

    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'max:50', Rule::unique('students', 'nis')->ignore($this->editingId)],
            'name' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'exists:classes,id'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', Rule::in(['L', 'P'])],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(Student $student): void
    {
        $this->editingId = $student->id;
        $this->nis = $student->nis;
        $this->name = $student->name;
        $this->class_id = $student->class_id;
        $this->birth_date = $student->birth_date?->toDateString();
        $this->gender = $student->gender;
        $this->photo = null;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['photo']);

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('students');
        }

        $student = Student::query()->updateOrCreate(['id' => $this->editingId], $data);

        if ($this->photo && $this->editingId && $student->getOriginal('photo_path')) {
            Storage::disk('local')->delete($student->getOriginal('photo_path'));
        }

        $this->dispatch('toast', message: 'Data siswa berhasil disimpan.');
        $this->resetForm();
    }

    public function delete(Student $student): void
    {
        if ($student->photo_path) {
            Storage::disk('local')->delete($student->photo_path);
        }

        $student->delete();
        $this->dispatch('toast', message: 'Data siswa berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'nis', 'name', 'class_id', 'birth_date', 'gender', 'photo', 'showForm']);
        $this->resetValidation();
    }

    public function render()
    {
        $students = Student::query()
            ->with('classRoom')
            ->when($this->search, fn ($query) => $query->where(function ($inner) {
                $inner->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('nis', 'like', '%'.$this->search.'%');
            }))
            ->when($this->filterClass, fn ($query) => $query->where('class_id', $this->filterClass))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.students.index', [
            'students' => $students,
            'classes' => ClassRoom::query()->orderBy('level')->orderBy('name')->get(),
        ])->layout('components.app-layout', ['title' => 'Data Siswa']);
    }
}
