<?php

use App\Livewire\DiceTray;
use App\Models\User;
use Livewire\Livewire;

test('monopoly page renders the dice tray with a livewire roll action', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('monopoly'));

    $response->assertOk();
    $response->assertSee('id="roll-all-dice"', false);
    $response->assertSee('id="dice-1"', false);
    $response->assertSee('id="dice-2"', false);
    $response->assertSee('wire:click="roll"', false);
});

test('rolling the dice dispatches the board event with usable totals', function () {
    Livewire::test(DiceTray::class)
        ->call('roll')
        ->assertDispatched('monopoly-dice-rolled', function (string $name, array $params): bool {
            if (! isset($params['value1'], $params['value2'], $params['total'])) {
                return false;
            }

            return $params['value1'] >= 1
                && $params['value1'] <= 6
                && $params['value2'] >= 1
                && $params['value2'] <= 6
                && $params['total'] === $params['value1'] + $params['value2'];
        });
});
