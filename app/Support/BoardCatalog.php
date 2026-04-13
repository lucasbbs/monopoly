<?php

namespace App\Support;

use DomainException;

/**
 * @phpstan-type BoardSpace array{
 *     position: int,
 *     type: string,
 *     component: string,
 *     name: string,
 *     price?: int,
 *     base_rent?: int,
 *     color_group?: string,
 *     icon?: string,
 *     instructions?: string,
 *     slot?: string,
 *     red?: bool,
 *     blue?: bool,
 *     orange?: bool,
 *     banner?: bool
 * }
 * @phpstan-type ComponentSpace array{
 *     component: string,
 *     name?: string,
 *     price?: string,
 *     color?: string,
 *     icon?: string,
 *     instructions?: string,
 *     slot?: string,
 *     red?: bool,
 *     blue?: bool,
 *     orange?: bool
 * }
 */
class BoardCatalog
{
    public const TYPE_GO = 'go';

    public const TYPE_PROPERTY = 'property';

    public const TYPE_RAILROAD = 'railroad';

    public const TYPE_UTILITY = 'utility';

    public const TYPE_TAX = 'tax';

    public const TYPE_CHANCE = 'chance';

    public const TYPE_COMMUNITY_CHEST = 'community_chest';

    public const TYPE_JAIL = 'jail';

    public const TYPE_FREE_PARKING = 'free_parking';

    public const TYPE_GO_TO_JAIL = 'go_to_jail';

    /**
     * @var list<string>
     */
    private const OWNABLE_TYPES = [
        self::TYPE_PROPERTY,
        self::TYPE_RAILROAD,
        self::TYPE_UTILITY,
    ];

    /**
     * @var list<string>
     */
    private const OWNABLE_COLOR_GROUP_DISPLAY_ORDER = [
        'dark-purple',
        'light-blue',
        'purple',
        'orange',
        'red',
        'yellow',
        'green',
        'dark-blue',
        'light-gray',
        'white',
    ];

    /**
     * @var array<int, BoardSpace>
     */
    private const SPACES = [
        0 => ['position' => 0, 'type' => self::TYPE_GO, 'component' => 'space.go', 'name' => 'Go'],
        1 => ['position' => 1, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Mediterranean Avenue', 'price' => 50, 'base_rent' => 2, 'color_group' => 'dark-purple', 'banner' => true],
        2 => ['position' => 2, 'type' => self::TYPE_COMMUNITY_CHEST, 'component' => 'space.community-chest', 'name' => 'Community Chest', 'banner' => true],
        3 => ['position' => 3, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Baltic Avenue', 'price' => 50, 'base_rent' => 4, 'color_group' => 'dark-purple', 'banner' => true],
        4 => ['position' => 4, 'type' => self::TYPE_TAX, 'component' => 'space.fee', 'name' => 'Income Tax', 'price' => 200, 'instructions' => 'Pay 10%<br>or<br>$200'],
        5 => ['position' => 5, 'type' => self::TYPE_RAILROAD, 'component' => 'space.railroad', 'name' => 'Reading Railroad', 'price' => 200, 'base_rent' => 25, 'color_group' => 'light-gray', 'banner' => true],
        6 => ['position' => 6, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Oriental Avenue', 'price' => 100, 'base_rent' => 6, 'color_group' => 'light-blue', 'banner' => true],
        7 => ['position' => 7, 'type' => self::TYPE_CHANCE, 'component' => 'space.chance', 'name' => 'Chance', 'red' => true],
        8 => ['position' => 8, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Vermont Avenue', 'price' => 100, 'base_rent' => 6, 'color_group' => 'light-blue', 'banner' => true],
        9 => ['position' => 9, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Connecticut Avenue', 'price' => 120, 'base_rent' => 8, 'color_group' => 'light-blue', 'banner' => true],
        10 => ['position' => 10, 'type' => self::TYPE_JAIL, 'component' => 'space.jail', 'name' => 'Jail'],
        11 => ['position' => 11, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'St. Charles Place', 'price' => 140, 'base_rent' => 10, 'color_group' => 'purple', 'banner' => true],
        12 => ['position' => 12, 'type' => self::TYPE_UTILITY, 'component' => 'space.utility', 'name' => 'Electric Company', 'price' => 150, 'base_rent' => 10, 'color_group' => 'white', 'icon' => 'fa-lightbulb-o', 'banner' => true],
        13 => ['position' => 13, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'States Avenue', 'price' => 140, 'base_rent' => 10, 'color_group' => 'purple', 'banner' => true],
        14 => ['position' => 14, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Virginia Avenue', 'price' => 160, 'base_rent' => 12, 'color_group' => 'purple', 'banner' => true],
        15 => ['position' => 15, 'type' => self::TYPE_RAILROAD, 'component' => 'space.railroad', 'name' => 'Pennsylvania Railroad', 'price' => 200, 'base_rent' => 25, 'color_group' => 'light-gray', 'banner' => true],
        16 => ['position' => 16, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'St. James Avenue', 'price' => 180, 'base_rent' => 14, 'color_group' => 'orange', 'banner' => true],
        17 => ['position' => 17, 'type' => self::TYPE_COMMUNITY_CHEST, 'component' => 'space.community-chest', 'name' => 'Community Chest'],
        18 => ['position' => 18, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Tennessee Avenue', 'price' => 180, 'base_rent' => 14, 'color_group' => 'orange', 'banner' => true],
        19 => ['position' => 19, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'New York Avenue', 'price' => 200, 'base_rent' => 16, 'color_group' => 'orange', 'banner' => true],
        20 => ['position' => 20, 'type' => self::TYPE_FREE_PARKING, 'component' => 'space.free-parking', 'name' => 'Free Parking'],
        21 => ['position' => 21, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Kentucky Avenue', 'price' => 220, 'base_rent' => 18, 'color_group' => 'red', 'banner' => true],
        22 => ['position' => 22, 'type' => self::TYPE_CHANCE, 'component' => 'space.chance', 'name' => 'Chance', 'blue' => true],
        23 => ['position' => 23, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Indiana Avenue', 'price' => 220, 'base_rent' => 18, 'color_group' => 'red', 'banner' => true],
        24 => ['position' => 24, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Illinois Avenue', 'price' => 200, 'base_rent' => 20, 'color_group' => 'red', 'banner' => true],
        25 => ['position' => 25, 'type' => self::TYPE_RAILROAD, 'component' => 'space.railroad', 'name' => 'B & O Railroad', 'price' => 200, 'base_rent' => 25, 'color_group' => 'light-gray', 'banner' => true],
        26 => ['position' => 26, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Atlantic Avenue', 'price' => 260, 'base_rent' => 22, 'color_group' => 'yellow', 'banner' => true],
        27 => ['position' => 27, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Ventnor Avenue', 'price' => 260, 'base_rent' => 22, 'color_group' => 'yellow', 'banner' => true],
        28 => ['position' => 28, 'type' => self::TYPE_UTILITY, 'component' => 'space.utility', 'name' => 'Waterworks', 'price' => 120, 'base_rent' => 10, 'color_group' => 'white', 'icon' => 'fa-tint', 'banner' => true],
        29 => ['position' => 29, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Marvin Gardens', 'price' => 280, 'base_rent' => 24, 'color_group' => 'yellow', 'banner' => true],
        30 => ['position' => 30, 'type' => self::TYPE_GO_TO_JAIL, 'component' => 'space.go-to-jail', 'name' => 'Go To Jail'],
        31 => ['position' => 31, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Pacific Avenue', 'price' => 300, 'base_rent' => 26, 'color_group' => 'green', 'banner' => true],
        32 => ['position' => 32, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'North Carolina Avenue', 'price' => 300, 'base_rent' => 26, 'color_group' => 'green', 'banner' => true],
        33 => ['position' => 33, 'type' => self::TYPE_COMMUNITY_CHEST, 'component' => 'space.community-chest', 'name' => 'Community Chest'],
        34 => ['position' => 34, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Pennsylvania Avenue', 'price' => 320, 'base_rent' => 28, 'color_group' => 'green', 'banner' => true],
        35 => ['position' => 35, 'type' => self::TYPE_RAILROAD, 'component' => 'space.railroad', 'name' => 'Short Line', 'price' => 200, 'base_rent' => 25, 'color_group' => 'light-gray', 'banner' => true],
        36 => ['position' => 36, 'type' => self::TYPE_CHANCE, 'component' => 'space.chance', 'name' => 'Chance', 'orange' => true],
        37 => ['position' => 37, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Park Place', 'price' => 350, 'base_rent' => 35, 'color_group' => 'dark-blue', 'banner' => true],
        38 => ['position' => 38, 'type' => self::TYPE_TAX, 'component' => 'space.fee', 'name' => 'Luxury Tax', 'price' => 75, 'instructions' => 'Pay $75.00', 'slot' => '<i class="drawing fa fa-diamond"></i>'],
        39 => ['position' => 39, 'type' => self::TYPE_PROPERTY, 'component' => 'space.property', 'name' => 'Boardwalk', 'price' => 400, 'base_rent' => 50, 'color_group' => 'dark-blue', 'banner' => true],
    ];

    private function __construct() {}

    /**
     * @return list<BoardSpace>
     */
    public static function all(): array
    {
        return array_values(self::SPACES);
    }

    /**
     * @return list<ComponentSpace>
     */
    public static function bannerSpaces(): array
    {
        return array_values(array_map(
            fn (array $space): array => self::toComponentSpace($space),
            array_filter(
                self::all(),
                fn (array $space): bool => ($space['banner'] ?? false) === true,
            ),
        ));
    }

    /**
     * @return array<string, list<BoardSpace>>
     */
    public static function propertyGroupedByColorGroup(): array
    {
        $groups = array_reduce(
            self::all(),
            function (array $groups, array $space): array {
                if (! self::isOwnable($space)) {
                    return $groups;
                }

                $colorGroup = $space['color_group'] ?? null;

                if ($colorGroup === null) {
                    return $groups;
                }

                $groups[$colorGroup] ??= [];
                $groups[$colorGroup][] = $space;

                return $groups;
            },
            [],
        );

        $orderedGroups = [];

        foreach (self::OWNABLE_COLOR_GROUP_DISPLAY_ORDER as $colorGroup) {
            $orderedGroups[$colorGroup] = $groups[$colorGroup];
            unset($groups[$colorGroup]);
        }

        return array_merge($orderedGroups, $groups);
    }

    /**
     * @return list<BoardSpace>
     */
    public static function ownableSpaces(): array
    {
        return array_values(array_filter(
            self::all(),
            fn (array $space): bool => self::isOwnable($space),
        ));
    }

    /**
     * @return BoardSpace
     */
    public static function spaceAt(int $position): array
    {
        return self::SPACES[$position] ?? throw new DomainException("Unknown board position [{$position}].");
    }

    public static function count(): int
    {
        return count(self::SPACES);
    }

    /**
     * @param  BoardSpace  $space
     */
    public static function isOwnable(array $space): bool
    {
        return in_array($space['type'], self::OWNABLE_TYPES, true);
    }

    /**
     * @param  BoardSpace  $space
     */
    public static function isTax(array $space): bool
    {
        return $space['type'] === self::TYPE_TAX;
    }

    /**
     * @param  BoardSpace  $space
     */
    public static function isGoToJail(array $space): bool
    {
        return $space['type'] === self::TYPE_GO_TO_JAIL;
    }

    /**
     * @param  BoardSpace  $space
     */
    public static function isChance(array $space): bool
    {
        return $space['type'] === self::TYPE_CHANCE;
    }

    /**
     * @param  BoardSpace  $space
     */
    public static function isCommunityChest(array $space): bool
    {
        return $space['type'] === self::TYPE_COMMUNITY_CHEST;
    }

    /**
     * @param  BoardSpace  $space
     * @return ComponentSpace
     */
    private static function toComponentSpace(array $space): array
    {
        return array_filter([
            'component' => $space['component'],
            'name' => $space['name'],
            'price' => self::priceLabel($space['price'] ?? null),
            'color' => $space['color_group'] ?? null,
            'icon' => $space['icon'] ?? null,
            'instructions' => $space['instructions'] ?? null,
            'slot' => $space['slot'] ?? null,
            'red' => $space['red'] ?? false,
            'blue' => $space['blue'] ?? false,
            'orange' => $space['orange'] ?? false,
        ], fn (mixed $value): bool => $value !== null && $value !== false);
    }

    private static function priceLabel(?int $price): ?string
    {
        if ($price === null) {
            return null;
        }

        return '$'.$price;
    }
}
