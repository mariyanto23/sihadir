<?php

namespace App\Livewire\Parent;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.parent.profile', [
            'user' => auth()->user()->load('profile'),
        ])->layout('components.app-layout', ['title' => 'Profil']);
    }
}
