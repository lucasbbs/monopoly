<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GamePlayer>
 */
class GamePlayerFactory extends Factory
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
            'turn_order' => 1,
            'token' => fake()->randomElement(array_keys(GamePlayer::tokenOptions())),
            'is_ready' => false,
            'cash' => 1500,
            'position' => 0,
            'in_jail' => false,
            'jail_turns' => 0,
            'is_bankrupt' => false,
        ];
    }
}
