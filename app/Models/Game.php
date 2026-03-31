<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory;

    public const STATUS_WAITING = 'waiting';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_FINISHED = 'finished';

    protected $table = 'games';

    protected $fillable = [
        'status',
        'current_turn_player_id',
        'winner_player_id',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function currentTurnPlayer(): BelongsTo
    {
        return $this->belongsTo(GamePlayer::class, 'current_turn_player_id');
    }

    public function winnerPlayer(): BelongsTo
    {
        return $this->belongsTo(GamePlayer::class, 'winner_player_id');
    }

    public function isWaiting(): bool
    {
        return $this->status === self::STATUS_WAITING;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isFinished(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function start(): self
    {
        if (!$this->isWaiting()) {
            throw new \DomainException('Only waiting games can be started.');
        }
        $firstPlayer = $this->players()->orderBy('turn_order')->first();

        if (!$firstPlayer) {
            throw new \DomainException('Cannot start a game without players.');
        }

        $this->current_turn_player_id = $firstPlayer->id;
        $this->status = self::STATUS_IN_PROGRESS;
        $this->started_at = now();
        $this->save();

        return $this;
    }

    public function finish(?GamePlayer $winner = null): self
    {
        if (!$this->isInProgress()) {
            throw new \DomainException('Only in-progress games can be finished.');
        }

        $this->status = self::STATUS_FINISHED;
        $this->ended_at = now();
        $this->winner_player_id = $winner?->id;
        $this->save();

        return $this;
    }

    public function advanceTurn(): self
    {
        if (!$this->isInProgress()) {
            throw new \DomainException('Only in-progress games can advance turns.');
        }

        $players = $this->players()
                    ->where('is_bankrupt', false)
                    ->orderBy('turn_order')
                    ->get();

        if ($players->isEmpty()) {
            throw new \DomainException('No players available to advance turn.');
        }

        if ($players->count() === 1) {
            return $this->finish($players->first());
        }

        $currentIndex = $players->search(
            fn (GamePlayer $player) => $player->id === $this->current_turn_player_id
        );

        if ($currentIndex === false) {
            $nextPlayer = $players->first();
        } else {
            $nextPlayer = $players[($currentIndex + 1) % $players->count()];
        }

        $this->current_turn_player_id = $nextPlayer->id;
        $this->save();

        return $this;
    }
}

