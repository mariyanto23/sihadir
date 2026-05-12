<?php

namespace App\Livewire\Parent;

use Livewire\Component;

class Children extends Component
{
    public function render()
    {
        return view('livewire.parent.children', [
            'children' => auth()->user()->children()->with('classRoom')->get(),
        ])->layout('components.app-layout', ['title' => 'Data Anak']);
    }
}
