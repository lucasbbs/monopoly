<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use App\Models\GamePlayer;
use Illuminate\Auth\Access\Response;

class GamePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Game $game): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can join the model.
     */
    public function join(User $user, Game $game): bool
    {
        $player = GamePlayer::query()->where('user_id', $user->id)->first();

        if (!$player) {
            $player = new GamePlayer();
            $player->user_id = $user->id;
        }

        return $game->isWaiting() && $game->players()->count() < $game->max_players && !$game->players()->where('user_id', $player->user_id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Game $game): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Game $game): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Game $game): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Game $game): bool
    {
        return false;
    }
}
