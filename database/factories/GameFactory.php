<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'max_players' => 1,
            'status' => Game::STATUS_WAITING,
            'current_turn_player_id' => null,
            'winner_player_id' => null,
            'started_at' => null,
            'ended_at' => null,
        ];
    }
}
