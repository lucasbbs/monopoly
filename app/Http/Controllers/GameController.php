<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\User;
use App\Services\GameEngine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GameController extends Controller
{
    public function play(Request $request, GameEngine $gameEngine): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $game = Game::query()
            ->whereHas('players', fn ($query) => $query->where('user_id', $user->id))
            ->whereIn('status', [Game::STATUS_WAITING, Game::STATUS_IN_PROGRESS])
            ->latest('updated_at')
            ->first();

        if (! $game instanceof Game) {
            $game = $this->createSinglePlayerGame($user, $gameEngine);
        }

        return $this->renderGame($user, $game, $gameEngine);
    }

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

        return view('game', [
            'game' => $game->load(['players.user', 'currentTurnPlayer.user', 'winnerPlayer.user']),
        ]);
    }

    protected function createSinglePlayerGame(User $user, GameEngine $gameEngine): Game
    {
        return DB::transaction(function () use ($user, $gameEngine): Game {
            $game = Game::query()->create([
                'name' => $user->name.' Solo Game',
                'max_players' => 1,
                'status' => Game::STATUS_WAITING,
            ]);

            $game->players()->create([
                'user_id' => $user->id,
                'turn_order' => 1,
                'token' => GamePlayer::TOKEN_TOP_HAT,
                'is_ready' => true,
            ]);

            return $gameEngine->startGame($game);
        });
    }
}
