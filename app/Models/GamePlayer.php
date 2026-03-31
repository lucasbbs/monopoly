<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamePlayer extends Model
{
    /** @use HasFactory<\Database\Factories\GamePlayerFactory> */
    use HasFactory;

    protected $table = 'game_players';

    public const TOKEN_BOOT = 'boot';
    public const TOKEN_CAR = 'car';
    public const TOKEN_CAT = 'cat';
    public const TOKEN_DOG = 'dog';
    public const TOKEN_DUCK = 'duck';
    public const TOKEN_HORSE = 'horse';
    public const TOKEN_IRON = 'iron';
    public const TOKEN_PENGUIN = 'penguin';
    public const TOKEN_SHIP = 'ship';
    public const TOKEN_TOP_HAT = 'top_hat';
    public const TOKEN_T_REX = 't_rex';
    public const TOKEN_WHEEL_BARROW = 'wheel_barrow';

    protected $fillable = [
        'game_id',
        'user_id',
        'turn_order',
        'token',
        'cash',
        'position',
        'in_jail',
        'jail_turns',
        'is_bankrupt',
    ];

    protected $casts = [
        'game_id' => 'integer',
        'user_id' => 'integer',
        'turn_order' => 'integer',
        'cash' => 'integer',
        'position' => 'integer',
        'jail_turns' => 'integer',
        'in_jail' => 'boolean',
        'is_bankrupt' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isInJail(): bool
    {
        return $this->in_jail;
    }

    public function isBankrupt(): bool
    {
        return $this->is_bankrupt;
    }

    public function canPlay(): bool
    {
        return ! $this->is_bankrupt;
    }

    public function sendToJail()
    {
        $this->in_jail = true;
        $this->jail_turns = 0;
        $this->save();
    }

    public function releaseFromJail()
    {
        $this->in_jail = false;
        $this->jail_turns = 0;
        $this->save();
    }

     public function incrementJailTurns()
    {
        $this->jail_turns += 1;
        $this->save();
    }

     public function addCash(int $amount): void
    {
        $this->cash += $amount;
        $this->save();
    }

    public function subtractCash(int $amount): void
    {
        $this->cash -= $amount;
        $this->save();
    }

}
