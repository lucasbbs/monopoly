<?php

namespace App\Models;

use App\Support\BoardCatalog;
use Database\Factories\PropertyOwnershipFactory;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyOwnership extends Model
{
    /** @use HasFactory<PropertyOwnershipFactory> */
    use HasFactory;

    protected $fillable = [
        'game_id',
        'space_position',
        'owner_game_player_id',
        'houses',
        'hotel',
        'mortgaged',
    ];

    protected $casts = [
        'game_id' => 'integer',
        'space_position' => 'integer',
        'owner_game_player_id' => 'integer',
        'houses' => 'integer',
        'hotel' => 'boolean',
        'mortgaged' => 'boolean',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function space(): array
    {
        if ($this->space_position === null) {
            throw new DomainException('Property ownership is missing a board space position.');
        }

        return BoardCatalog::spaceAt($this->space_position);
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

    public function unmortgage(): void
    {
        $this->mortgaged = false;
        $this->save();
    }
}
