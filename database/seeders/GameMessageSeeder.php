<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameMessage;
use Illuminate\Database\Seeder;

class GameMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::query()
            ->with('players')
            ->get()
            ->each(function (Game $game): void {
                $authorId = $game->players->first()?->user_id;

                if ($authorId === null) {
                    return;
                }

                GameMessage::factory()
                    ->count(2)
                    ->for($game)
                    ->state([
                        'user_id' => $authorId,
                    ])
                    ->create();
            });
    }
}
