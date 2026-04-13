<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\User;
use App\Services\GameEngine;
use Livewire\Component;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PlayGame extends Component
{
    public $game;

    public function endTurn() {}

    public function show(Request $request, Game $game, GameEngine $gameEngine): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        return $this->renderGame($user, $game, $gameEngine);
    }

    protected function renderGame(User $user, Game $game, GameEngine $gameEngine): View|RedirectResponse
    {
        abort_unless(
            $game->players()->where('user_id', $user->id)->exists(),
            404,
        );

        if ($game->isWaiting()) {
            if (! $game->isSinglePlayer()) {
                return redirect()->route('monopoly.invite', $game);
            }

            $game = $gameEngine->startGame($game);
        }

        return view('play-game', [
            'game' => $game->load(['players.user', 'currentTurnPlayer.user', 'winnerPlayer.user']),
        ]);
    }

    public function rollDice()
    {
        return [random_int(1, 6), random_int(1, 6)];
    }

    public function mount(Game $game)
    {
        $this->game = $game;
    }

    public function render()
    {
        return view('livewire.play-game');
    }
}
