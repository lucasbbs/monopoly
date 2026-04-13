<?php

use App\Support\BoardCatalog;

test('it exposes a forty-space board in position order', function () {
    $spaces = BoardCatalog::all();

    expect($spaces)->toHaveCount(40);
    expect($spaces[0]['name'])->toBe('Go');
    expect($spaces[39]['name'])->toBe('Boardwalk');
});

test('it exposes ownable and banner spaces without database records', function () {
    $ownablePositions = array_column(BoardCatalog::ownableSpaces(), 'position');
    $bannerSpaces = BoardCatalog::bannerSpaces();

    expect($ownablePositions)->toHaveCount(28);
    expect($ownablePositions)->toContain(1, 5, 12, 39);
    expect($ownablePositions)->not()->toContain(0, 2, 4, 10, 20, 30);
    expect($bannerSpaces)->toHaveCount(29);
    expect($bannerSpaces)->toContainEqual([
        'component' => 'space.property',
        'name' => 'Mediterranean Avenue',
        'price' => '$50',
        'color' => 'dark-purple',
    ]);
});

test('it groups ownable spaces by color group', function () {
    $spacesByColorGroup = BoardCatalog::propertyGroupedByColorGroup();

    expect(array_keys($spacesByColorGroup))->toBe([
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
    ]);

    expect(array_map(
        fn (array $spaces): array => array_column($spaces, 'position'),
        $spacesByColorGroup,
    ))->toBe([
        'dark-purple' => [1, 3],
        'light-blue' => [6, 8, 9],
        'purple' => [11, 13, 14],
        'orange' => [16, 18, 19],
        'red' => [21, 23, 24],
        'yellow' => [26, 27, 29],
        'green' => [31, 32, 34],
        'dark-blue' => [37, 39],
        'light-gray' => [5, 15, 25, 35],
        'white' => [12, 28],
    ]);
});

test('it throws for an unknown board position', function () {
    BoardCatalog::spaceAt(99);
})->throws(DomainException::class);
