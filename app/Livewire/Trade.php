<?php

namespace App\Livewire;

use App\Support\BoardCatalog;
use Livewire\Component;

class Trade extends Component
{
    public $properties = null;

    public function mount()
    {
        $this->properties = BoardCatalog::propertyGroupedByColorGroup();
    }

    public function render()
    {
        return view('livewire.trade', ['properties' => $this->properties]);
    }
}
