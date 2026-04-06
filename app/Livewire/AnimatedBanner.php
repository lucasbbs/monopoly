<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class AnimatedBanner extends Component
{
    /**
     * @var array<int, array<string, bool|string>>
     */
    public array $randomSpaces = [];

    /**
     * @var array<int, array<string, bool|string>>
     */
    private const AVAILABLE_SPACES = [
        ['component' => 'space.property', 'name' => 'Mediterranean Avenue', 'price' => '$50', 'color' => 'dark-purple'],
        ['component' => 'space.community-chest'],
        ['component' => 'space.property', 'name' => 'Baltic Avenue', 'price' => '$50', 'color' => 'dark-purple'],
        ['component' => 'space.railroad', 'name' => 'Reading Railroad', 'price' => '$200'],
        ['component' => 'space.property', 'name' => 'Oriental Avenue', 'price' => '$100', 'color' => 'light-blue'],
        ['component' => 'space.property', 'name' => 'Vermont Avenue', 'price' => '$100', 'color' => 'light-blue'],
        ['component' => 'space.property', 'name' => 'Connecticut Avenue', 'price' => '$120', 'color' => 'light-blue'],
        ['component' => 'space.property', 'name' => 'St. Charles Place', 'price' => '$140', 'color' => 'purple'],
        ['component' => 'space.utility', 'name' => 'Electric Company', 'icon' => 'fa-lightbulb-o', 'price' => '$150'],
        ['component' => 'space.property', 'name' => 'States Avenue', 'price' => '$140', 'color' => 'purple'],
        ['component' => 'space.property', 'name' => 'Virginia Avenue', 'price' => '$160', 'color' => 'purple'],
        ['component' => 'space.railroad', 'name' => 'Pennsylvania Railroad', 'price' => '$200'],
        ['component' => 'space.property', 'name' => 'St. James Avenue', 'price' => '$180', 'color' => 'orange'],
        ['component' => 'space.property', 'name' => 'Tennessee Avenue', 'price' => '$180', 'color' => 'orange'],
        ['component' => 'space.property', 'name' => 'New York Avenue', 'price' => '$200', 'color' => 'orange'],
        ['component' => 'space.property', 'name' => 'Kentucky Avenue', 'price' => '$220', 'color' => 'red'],
        ['component' => 'space.property', 'name' => 'Indiana Avenue', 'price' => '$220', 'color' => 'red'],
        ['component' => 'space.property', 'name' => 'Illinois Avenue', 'price' => '$200', 'color' => 'red'],
        ['component' => 'space.railroad', 'name' => 'B & O Railroad', 'price' => '$200'],
        ['component' => 'space.property', 'name' => 'Atlantic Avenue', 'price' => '$260', 'color' => 'yellow'],
        ['component' => 'space.property', 'name' => 'Ventnor Avenue', 'price' => '$260', 'color' => 'yellow'],
        ['component' => 'space.utility', 'name' => 'Waterworks', 'icon' => 'fa-tint', 'price' => '$120'],
        ['component' => 'space.property', 'name' => 'Marvin Gardens', 'price' => '$280', 'color' => 'yellow'],
        ['component' => 'space.property', 'name' => 'Pacific Avenue', 'price' => '$300', 'color' => 'green'],
        ['component' => 'space.property', 'name' => 'North Carolina Avenue', 'price' => '$300', 'color' => 'green'],
        ['component' => 'space.property', 'name' => 'Pennsylvania Avenue', 'price' => '$320', 'color' => 'green'],
        ['component' => 'space.railroad', 'name' => 'Short Line', 'price' => '$200'],
        ['component' => 'space.property', 'name' => 'Park Place', 'price' => '$350', 'color' => 'dark-blue'],
        ['component' => 'space.property', 'name' => 'Boardwalk', 'price' => '$400', 'color' => 'dark-blue'],
    ];

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
        $maxIndex = count(self::AVAILABLE_SPACES) - 1;

        $this->randomSpaces = array_map(
            fn (): array => self::AVAILABLE_SPACES[random_int(0, $maxIndex)],
            range(1, 20),
        );
    }

    public function refreshSpaces(): void {
        $this->randomSpaces[array_rand($this->randomSpaces)] = self::AVAILABLE_SPACES[random_int(0, count(self::AVAILABLE_SPACES) - 1)];
    }
}
