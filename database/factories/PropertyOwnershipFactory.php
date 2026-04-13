<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\PropertyOwnership;
use App\Support\BoardCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyOwnership>
 */
class PropertyOwnershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'space_position' => fake()->randomElement(array_column(BoardCatalog::ownableSpaces(), 'position')),
            'owner_game_player_id' => null,
            'houses' => 0,
            'hotel' => false,
            'mortgaged' => false,
        ];
    }
}
