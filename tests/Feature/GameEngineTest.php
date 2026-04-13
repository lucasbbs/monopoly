<?php

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\PropertyOwnership;
use App\Services\GameEngine;
use App\Support\BoardCatalog;

test('starting a game creates ownership rows for every ownable board position', function () {
    $game = Game::factory()->create([
        'max_players' => 1,
        'status' => Game::STATUS_WAITING,
    ]);

    $player = GamePlayer::factory()->create([
        'game_id' => $game->id,
        'turn_order' => 1,
        'position' => 0,
    ]);

    $startedGame = app(GameEngine::class)->startGame($game);

    $ownerships = PropertyOwnership::query()
        ->where('game_id', $game->id)
        ->orderBy('space_position')
        ->get();

    expect($startedGame->current_turn_player_id)->toBe($player->id);
    expect($ownerships)->toHaveCount(count(BoardCatalog::ownableSpaces()));
    expect($ownerships->pluck('space_position')->all())
        ->toBe(array_column(BoardCatalog::ownableSpaces(), 'position'));
});

test('buying the current property uses the catalog price and space position', function () {
    $game = Game::factory()->create([
        'status' => Game::STATUS_IN_PROGRESS,
    ]);

    $player = GamePlayer::factory()->create([
        'game_id' => $game->id,
        'turn_order' => 1,
        'cash' => 1500,
        'position' => 1,
    ]);

    $game->update([
        'current_turn_player_id' => $player->id,
    ]);

    $ownership = PropertyOwnership::factory()->create([
        'game_id' => $game->id,
        'space_position' => 1,
    ]);

    $purchasedOwnership = app(GameEngine::class)->buyCurrentProperty($game->fresh());

    expect($purchasedOwnership->isOwned())->toBeTrue();
    expect($purchasedOwnership->owner_game_player_id)->toBe($player->id);
    expect($purchasedOwnership->space_position)->toBe($ownership->space_position);
    expect($player->fresh()->cash)->toBe(1450);
});
