<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyOwnership extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyOwnershipFactory> */
    use HasFactory;

    protected $fillable = [
        'game_id',
        'board_space_id',
        'owner_game_player_id',
        'houses',
        'hotel',
        'mortgaged',
    ];

    protected $casts = [
        'game_id' => 'integer',
        'board_space_id' => 'integer',
        'owner_game_player_id' => 'integer',
        'houses' => 'integer',
        'hotel' => 'boolean',
        'mortgaged' => 'boolean',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function boardSpace(): BelongsTo
    {
        return $this->belongsTo(BoardSpace::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(GamePlayer::class, 'owner_game_player_id');
    }

    public function isOwned(): bool
    {
        return $this->owner_game_player_id !== null;
    }

    public function isMortgaged(): bool
    {
        return $this->mortgaged;
    }

     public function hasHotel(): bool
    {
        return $this->hotel;
    }

     public function houseCount(): int
    {
        return $this->houses;
    }

    public function assignOwner(GamePlayer $gamePlayer): void
    {
        $this->owner_game_player_id = $gamePlayer->id;
        $this->save();
    }

    public function unmortgage()
    {
        $this->mortgaged = false;
        $this->save();
    }

}
