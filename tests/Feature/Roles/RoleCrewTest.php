<?php

beforeEach(function () {
    $this->seed();
});

it('has role crew', function () {
    $user = \App\Models\User::query()->find(14);
    $this->assertTrue($user->hasRole('crew'));
    $response = $this
        ->actingAs($user)
        ->get(route('dashboard'));

    $response->assertStatus(200);
});
