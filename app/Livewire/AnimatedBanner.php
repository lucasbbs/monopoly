<?php

namespace App\Livewire;

use App\Support\BoardCatalog;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AnimatedBanner extends Component
{
    /**
     * @var array<int, array<string, bool|string>>
     */
    public array $randomSpaces = [];

    public function mount(): void
    {
        $this->generateRandomSpaces();
    }

    public function render(): View
    {
        return view('livewire.animated-banner');
    }

    private function generateRandomSpaces(): void
    {
        $availableSpaces = BoardCatalog::bannerSpaces();
        $maxIndex = count($availableSpaces) - 1;

        $this->randomSpaces = array_map(
            fn (): array => $availableSpaces[random_int(0, $maxIndex)],
            range(1, 20),
        );
    }

    public function refreshSpaces(): void
    {
        $availableSpaces = BoardCatalog::bannerSpaces();

        $this->randomSpaces[array_rand($this->randomSpaces)] = $availableSpaces[random_int(0, count($availableSpaces) - 1)];
    }
}
