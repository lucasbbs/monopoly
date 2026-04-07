<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameMessage>
 */
class GameMessageFactory extends Factory
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
            'user_id' => User::factory(),
            'message' => fake()->sentence(),
        ];
    }
}
