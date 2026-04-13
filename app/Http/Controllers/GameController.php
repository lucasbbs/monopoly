<?php

namespace App\Http\Controllers;

use App\Events\GameLifecycleEvent;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\User;
use App\Services\GameEngine;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class GameController extends Controller
{

    public function index(): View|RedirectResponse
    {
        return view('dashboard', [
            'games' => Game::query()
                ->whereIn('status', [Game::STATUS_WAITING, Game::STATUS_IN_PROGRESS])
                ->latest('updated_at')
                ->get(),
        ]);

    }

    public function join(Request $request, Game $game): RedirectResponse
    {
        Gate::authorize('join', $game);
        /** @var User $user */
        $user = $request->user();

        // if ($game->isFull()) {
        //     return redirect()->route('dashboard')->with('error', __('Game is full'));
        // }

        $game->players()->create([
            'user_id' => $user->id,
            'turn_order' => $game->players()->count() + 1,
            'token' => GamePlayer::TOKEN_TOP_HAT,
        ]);

        GameLifecycleEvent::dispatch($game->id, 'player_joined', [
            'player_id' => $user->id,
        ]);

        return redirect()->route('monopoly.show', $game);
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
