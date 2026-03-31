<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardSpace extends Model
{
    /** @use HasFactory<\Database\Factories\BoardSpaceFactory> */
    use HasFactory;

    public const TYPE_PROPERTY = 'property';
    public const TYPE_RAILROAD = 'railroad';
    public const TYPE_UTILITY = 'utility';
    public const TYPE_TAX = 'tax';
    public const TYPE_CHANCE = 'chance';
    public const TYPE_COMMUNITY_CHEST = 'community_chest';
    public const TYPE_JAIL = 'jail';
    public const TYPE_FREE_PARKING = 'free_parking';
    public const TYPE_GO_TO_JAIL = 'go_to_jail';

    public const TYPES = [
        self::TYPE_PROPERTY,
        self::TYPE_RAILROAD,
        self::TYPE_UTILITY,
        self::TYPE_TAX,
        self::TYPE_CHANCE,
        self::TYPE_COMMUNITY_CHEST,
        self::TYPE_JAIL,
        self::TYPE_FREE_PARKING,
        self::TYPE_GO_TO_JAIL,
    ];

    protected $fillable = [
        'name',
        'position',
        'type',
        'color_group',
        'price',
        'base_rent',
    ];

    protected $casts = [
        'position' => 'integer',
        'price' => 'integer',
        'base_rent' => 'integer',
    ];

    public function isProperty(): bool
    {
        return $this->type === self::TYPE_PROPERTY;
    }

    public function isRailroad(): bool
    {
        return $this->type === self::TYPE_RAILROAD;
    }

    public function isUtility(): bool
    {
        return $this->type === self::TYPE_UTILITY;
    }

    public function isTax(): bool
    {
        return $this->type === self::TYPE_TAX;
    }

    public function isChance(): bool
    {
        return $this->type === self::TYPE_CHANCE;
    }

    public function isCommunityChest(): bool
    {
        return $this->type === self::TYPE_COMMUNITY_CHEST;
    }

    public function isJail(): bool
    {
        return $this->type === self::TYPE_JAIL;
    }

    public function isFreeParking(): bool
    {
        return $this->type === self::TYPE_FREE_PARKING;
    }

    public function isGoToJail(): bool
    {
        return $this->type === self::TYPE_GO_TO_JAIL;
    }

    public function isOwnable(): bool
    {
        return in_array($this->type, [
            self::TYPE_PROPERTY,
            self::TYPE_RAILROAD,
            self::TYPE_UTILITY
        ]);
    }

    public function isColorGroup(string $colorGroup): bool
    {
        return $this->color_group === $colorGroup;
    }
}
