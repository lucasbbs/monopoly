<?php

use App\Livewire\AnimatedBanner;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('animated banner renders board space components', function () {
    Livewire::test(AnimatedBanner::class)
        ->set('randomSpaces', [
            [
                'component' => 'space.fee',
                'name' => 'Luxury Tax',
                'instructions' => 'Pay $75.00',
                'slot' => '<i class="drawing fa fa-diamond"></i>',
            ],
            [
                'component' => 'space.property',
                'name' => 'Marvin Gardens',
                'price' => '$280',
                'color' => 'yellow',
            ],
            [
                'component' => 'space.chance',
                'orange' => true,
            ],
        ])
        ->assertSeeHtml('animated-banner__space space fee')
        ->assertSeeHtml('animated-banner__space space property')
        ->assertSeeHtml('animated-banner__space space chance')
        ->assertSee('Luxury Tax')
        ->assertSee('Marvin Gardens')
        ->assertSee('Chance');
});
