<?php

namespace App\Livewire;

use Livewire\Component;

class CreateGame extends Component
{


    public function render()
    {
        return view('livewire.create-game')->layout('layouts::app', ['title' => __('Create Game')]);
    }
}
