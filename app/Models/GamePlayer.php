<?php

namespace App\Models;

use Database\Factories\GamePlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamePlayer extends Model
{
    /** @use HasFactory<GamePlayerFactory> */
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

    public const JAIL_POSITION = 10;

    /**
     * @return array<string, string>
     */
    public static function tokenOptions(): array
    {
        return [
            self::TOKEN_BOOT => 'Boot',
            self::TOKEN_CAR => 'Car',
            self::TOKEN_CAT => 'Cat',
            self::TOKEN_DOG => 'Dog',
            self::TOKEN_DUCK => 'Duck',
            self::TOKEN_HORSE => 'Horse',
            self::TOKEN_IRON => 'Iron',
            self::TOKEN_PENGUIN => 'Penguin',
            self::TOKEN_SHIP => 'Ship',
            self::TOKEN_TOP_HAT => 'TopHat',
            self::TOKEN_T_REX => 'TRex',
            self::TOKEN_WHEEL_BARROW => 'WheelBarrow',
        ];
    }

    protected $fillable = [
        'game_id',
        'user_id',
        'turn_order',
        'token',
        'is_ready',
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
        'is_ready' => 'boolean',
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

    public function isReady(): bool
    {
        return $this->is_ready;
    }

    public function sendToJail(): void
    {
        $this->update([
            'position' => self::JAIL_POSITION,
            'in_jail' => true,
            'jail_turns' => 0,
        ]);
    }

    public function releaseFromJail(): void
    {
        $this->update([
            'in_jail' => false,
            'jail_turns' => 0,
        ]);
    }

    public function incrementJailTurns(): void
    {
        $this->increment('jail_turns');
    }

    public function addCash(int $amount): void
    {
        $this->increment('cash', $amount);
        $this->refresh();
    }

    public function removeCash(int $amount): void
    {
        $this->decrement('cash', $amount);
        $this->refresh();
    }

    public function subtractCash(int $amount): void
    {
        $this->removeCash($amount);
    }

    public function moveTo(int $position): void
    {
        $this->update([
            'position' => $position,
        ]);
    }
}
