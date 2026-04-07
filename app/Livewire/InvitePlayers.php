<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\GameMessage;
use App\Models\GamePlayer;
use App\Models\User;
use App\Services\GameEngine;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class InvitePlayers extends Component
{
    public int $gameId;

    public string $selectedToken = '';

    public string $inviteEmail = '';

    public string $chatMessage = '';

    protected GameEngine $gameEngine;

    public function boot(GameEngine $gameEngine): void
    {
        $this->gameEngine = $gameEngine;
    }

    public function mount(Game $game): void
    {
        abort_unless(
            $game->players()->where('user_id', Auth::id())->exists(),
            404,
        );

        $this->gameId = $game->id;
        $this->selectedToken = $this->currentPlayer($game)->token;
    }

    public function updatedSelectedToken(string $token): void
    {
        $player = $this->resolveCurrentPlayer();

        if ($token === $player->token) {
            return;
        }

        if (! array_key_exists($token, GamePlayer::tokenOptions())) {
            $this->selectedToken = $player->token;

            $this->throwValidation('selectedToken', 'Choose one of the available tokens.');
        }

        if ($this->isTokenTaken($token, $player->id)) {
            $this->selectedToken = $player->token;

            $this->throwValidation('selectedToken', 'That token has already been claimed.');
        }

        $player->update([
            'token' => $token,
            'is_ready' => false,
        ]);

        session()->flash('status', 'Token updated. Ready status reset until you confirm again.');
    }

    public function invitePlayer(): void
    {
        $validated = $this->validate([
            'inviteEmail' => ['required', 'email:rfc', 'max:255'],
        ]);

        $game = $this->resolveGame();

        if (! $game->isWaiting()) {
            $this->throwValidation('inviteEmail', 'You can only add players before the game starts.');
        }

        if ($game->players()->count() >= $game->max_players) {
            $this->throwValidation('inviteEmail', 'This lobby is already full.');
        }

        $user = User::query()
            ->where('email', $validated['inviteEmail'])
            ->first();

        if (! $user instanceof User) {
            $this->throwValidation('inviteEmail', 'That email does not belong to a registered player.');
        }

        if ((int) $user->id === (int) Auth::id()) {
            $this->throwValidation('inviteEmail', 'You are already in this game.');
        }

        if ($game->players()->where('user_id', $user->id)->exists()) {
            $this->throwValidation('inviteEmail', 'That player is already in the lobby.');
        }

        $token = $this->firstAvailableToken($game);

        if ($token === null) {
            $this->throwValidation('inviteEmail', 'No tokens are available for the next player.');
        }

        $game->players()->create([
            'user_id' => $user->id,
            'turn_order' => ((int) $game->players()->max('turn_order')) + 1,
            'token' => $token,
            'is_ready' => false,
        ]);

        $this->inviteEmail = '';

        session()->flash('status', $user->name.' was added to the lobby.');
    }

    public function toggleReady(): void
    {
        $player = $this->resolveCurrentPlayer();

        if ($this->isTokenTaken($player->token, $player->id)) {
            $this->throwValidation('selectedToken', 'Pick a different token before marking yourself ready.');
        }

        $player->update([
            'is_ready' => ! $player->isReady(),
        ]);
    }

    public function sendMessage(): void
    {
        $validated = $this->validate([
            'chatMessage' => ['required', 'string', 'max:500'],
        ]);

        GameMessage::query()->create([
            'game_id' => $this->gameId,
            'user_id' => (int) Auth::id(),
            'message' => trim($validated['chatMessage']),
        ]);

        $this->chatMessage = '';
    }

    public function startGame()
    {
        try {
            $game = $this->gameEngine->startGame($this->resolveGame());
        } catch (DomainException $exception) {
            $this->throwValidation('startGame', $exception->getMessage());
        }

        return redirect()->route('monopoly.show', $game);
    }

    public function render(): View
    {
        $game = $this->resolveGame();
        $currentPlayer = $this->currentPlayer($game);
        $chatMessages = $game->messages()
            ->with('user')
            ->latest('id')
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        $claimedTokens = $game->players
            ->reject(fn (GamePlayer $player) => $player->id === $currentPlayer->id)
            ->mapWithKeys(fn (GamePlayer $player) => [
                $player->token => $player->user?->name ?? 'Claimed',
            ])
            ->all();

        $seatsRemaining = max($game->max_players - $game->players->count(), 0);
        $canStart = $game->isWaiting()
            && $seatsRemaining === 0
            && $game->players->every(fn (GamePlayer $player) => $player->isReady());

        return view('livewire.invite-players', [
            'game' => $game,
            'currentPlayer' => $currentPlayer,
            'tokenOptions' => GamePlayer::tokenOptions(),
            'claimedTokens' => $claimedTokens,
            'seatsRemaining' => $seatsRemaining,
            'canStart' => $canStart,
            'chatMessages' => $chatMessages,
        ])->layout('layouts::app', ['title' => __('Invite Players')]);
    }

    protected function resolveGame(): Game
    {
        return Game::query()
            ->with(['players.user'])
            ->whereKey($this->gameId)
            ->whereHas('players', fn ($query) => $query->where('user_id', Auth::id()))
            ->firstOrFail();
    }

    protected function resolveCurrentPlayer(): GamePlayer
    {
        return $this->currentPlayer($this->resolveGame());
    }

    protected function currentPlayer(Game $game): GamePlayer
    {
        $player = $game->players->firstWhere('user_id', Auth::id());

        abort_unless($player instanceof GamePlayer, 404);

        return $player;
    }

    protected function firstAvailableToken(Game $game): ?string
    {
        $takenTokens = $game->players->pluck('token')->all();

        foreach (array_keys(GamePlayer::tokenOptions()) as $token) {
            if (! in_array($token, $takenTokens, true)) {
                return $token;
            }
        }

        return null;
    }

    protected function isTokenTaken(string $token, int $exceptPlayerId): bool
    {
        return GamePlayer::query()
            ->where('game_id', $this->gameId)
            ->where('token', $token)
            ->where('id', '!=', $exceptPlayerId)
            ->exists();
    }

    protected function throwValidation(string $field, string $message): never
    {
        throw ValidationException::withMessages([
            $field => $message,
        ]);
    }
}
