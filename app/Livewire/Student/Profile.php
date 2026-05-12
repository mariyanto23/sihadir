<?php

namespace App\Livewire\Student;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.student.profile', [
            'user' => auth()->user()->load('profile', 'student.classRoom'),
        ])->layout('components.app-layout', ['title' => 'Profil']);
    }
}
