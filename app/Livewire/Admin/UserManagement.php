<?php

namespace App\Livewire\Admin;

use App\Models\Profile;
use App\Models\Student;
use App\Models\User;
use App\Support\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public string $name = '';
    public string $email = '';
    public string $password = 'password';
    public string $role = Role::STUDENT;
    public ?int $student_id = null;
    public array $children = [];

    public function createUser(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in([Role::STUDENT, Role::PARENT])],
            'student_id' => ['nullable', 'exists:students,id'],
            'children' => ['array'],
            'children.*' => ['exists:students,id'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole($data['role']);

        Profile::query()->create(['user_id' => $user->id, 'name' => $user->name, 'student_id' => $data['student_id']]);

        if ($data['role'] === Role::STUDENT && $data['student_id']) {
            Student::query()->whereKey($data['student_id'])->whereNull('user_id')->update(['user_id' => $user->id]);
        }

        if ($data['role'] === Role::PARENT) {
            $user->children()->sync($data['children']);
        }

        $this->reset(['name', 'email', 'password', 'student_id', 'children']);
        $this->password = 'password';
        $this->dispatch('toast', message: 'Akun berhasil dibuat.');
    }

    public function resetPassword(User $user): void
    {
        $user->update(['password' => Hash::make('password')]);
        $this->dispatch('toast', message: 'Password direset menjadi password.');
    }

    public function toggle(User $user): void
    {
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => User::query()->with('roles')->latest()->paginate(10),
            'students' => Student::query()->with('classRoom')->orderBy('name')->get(),
        ])->layout('components.app-layout', ['title' => 'Manajemen Akun']);
    }
}
