<?php

use App\Livewire\CreateGame;
use App\Livewire\InvitePlayers;
use App\Models\Game;
use App\Models\GameMessage;
use App\Models\GamePlayer;
use App\Models\User;
use Livewire\Livewire;

test('creating a game redirects to the invite players lobby', function () {
    $host = User::factory()->create();

    $this->actingAs($host);

    $component = Livewire::test(CreateGame::class)
        ->set('name', 'Family Showdown')
        ->set('maxPlayers', 4)
        ->call('createGame');

    $game = Game::query()->first();

    expect($game)->not->toBeNull()
        ->and($game?->name)->toBe('Family Showdown')
        ->and($game?->max_players)->toBe(4)
        ->and($game?->status)->toBe(Game::STATUS_WAITING);

    expect(GamePlayer::query()
        ->where('game_id', $game?->id)
        ->where('user_id', $host->id)
        ->where('turn_order', 1)
        ->where('token', GamePlayer::TOKEN_TOP_HAT)
        ->where('is_ready', false)
        ->exists())->toBeTrue();

    $component->assertRedirect(route('monopoly.invite', $game));
});

test('players can view their game lobby', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create([
        'name' => 'Friday Night Monopoly',
        'max_players' => 2,
    ]);

    GamePlayer::factory()
        ->for($game)
        ->for($user)
        ->create([
            'turn_order' => 1,
            'token' => GamePlayer::TOKEN_TOP_HAT,
        ]);

    $this->actingAs($user)
        ->get(route('monopoly.invite', $game))
        ->assertOk()
        ->assertSee('Friday Night Monopoly')
        ->assertSee('Choose Your Token')
        ->assertSee('Game Chat')
        ->assertSee('Start Game');
});

test('players can manage the lobby and start the game', function () {
    $host = User::factory()->create([
        'name' => 'Host Player',
    ]);
    $guest = User::factory()->create([
        'name' => 'Guest Player',
    ]);

    $game = Game::factory()->create([
        'name' => 'Weekend Table',
        'max_players' => 2,
    ]);

    $hostPlayer = GamePlayer::factory()
        ->for($game)
        ->for($host)
        ->create([
            'turn_order' => 1,
            'token' => GamePlayer::TOKEN_TOP_HAT,
            'is_ready' => false,
        ]);

    $this->actingAs($host);

    Livewire::test(InvitePlayers::class, ['game' => $game])
        ->set('selectedToken', GamePlayer::TOKEN_BOOT)
        ->set('chatMessage', 'Ready when you are.')
        ->call('sendMessage')
        ->set('inviteEmail', $guest->email)
        ->call('invitePlayer')
        ->call('toggleReady');

    expect($hostPlayer->fresh())
        ->token->toBe(GamePlayer::TOKEN_BOOT)
        ->is_ready->toBeTrue();

    expect(GameMessage::query()
        ->where('game_id', $game->id)
        ->where('user_id', $host->id)
        ->where('message', 'Ready when you are.')
        ->exists())->toBeTrue();

    $guestPlayer = GamePlayer::query()
        ->where('game_id', $game->id)
        ->where('user_id', $guest->id)
        ->first();

    expect($guestPlayer)->not->toBeNull()
        ->and($guestPlayer?->token)->toBe(GamePlayer::TOKEN_CAR)
        ->and($guestPlayer?->is_ready)->toBeFalse();

    $guestPlayer?->update([
        'is_ready' => true,
    ]);

    Livewire::test(InvitePlayers::class, ['game' => $game])
        ->call('startGame')
        ->assertRedirect(route('monopoly.show', $game));

    expect($game->fresh())
        ->status->toBe(Game::STATUS_IN_PROGRESS)
        ->current_turn_player_id->toBeInt();
});
