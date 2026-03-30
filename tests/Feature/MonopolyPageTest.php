<?php

use App\Models\User;

test('guests are redirected to the login page from monopoly', function () {
    $response = $this->get(route('monopoly'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view token controls on monopoly', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('monopoly'));

    $response->assertOk();
    $response->assertSee('Choose a token');
    $response->assertSee('data-token-picker', false);
    $response->assertSee('data-token-space', false);
    $response->assertSee('Previous space');
    $response->assertSee('Next space');
    $response->assertSee('Go (0)');
});
