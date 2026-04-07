<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateGame extends Component
{
    public string $name = '';

    public int|string $maxPlayers = '';

    public function createGame()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'maxPlayers' => ['required', 'integer', 'min:2', 'max:12'],
        ]);

        $game = DB::transaction(function () use ($validated): Game {
            $game = Game::query()->create([
                'name' => $validated['name'],
                'max_players' => (int) $validated['maxPlayers'],
                'status' => Game::STATUS_WAITING,
            ]);

            $game->players()->create([
                'user_id' => (int) Auth::id(),
                'turn_order' => 1,
                'token' => GamePlayer::TOKEN_TOP_HAT,
                'is_ready' => false,
            ]);

            return $game;
        });

        return redirect()->route('monopoly.invite', $game);
    }

    public function render(): View
    {
        return view('livewire.create-game')->layout('layouts::app', ['title' => __('Create Game')]);
    }
}
