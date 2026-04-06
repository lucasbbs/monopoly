<?php

namespace App\Livewire;

use App\Services\GameEngine;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DiceTray extends Component
{
    protected GameEngine $gameEngine;

    public function boot(GameEngine $gameEngine): void
    {
        $this->gameEngine = $gameEngine;
    }

    public int $value1 = 5;

    public int $value2 = 5;

    public function roll(): void
    {
        [ $this->value1, $this->value2] = $this->gameEngine->rollDice();

        $this->dispatch(
            'monopoly-dice-rolled',
            value1: $this->value1,
            value2: $this->value2,
            total: $this->value1 + $this->value2,
        );
    }

    public function render(): View
    {
        return view('livewire.dice-tray');
    }
}
