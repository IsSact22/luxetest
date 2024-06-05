<?php

beforeEach(function () {
    $this->seed();
});

it('has role cam', function () {
    $user = \App\Models\User::query()->find(3);
    $this->assertTrue($user->hasRole('cam'));
    $response = $this
        ->actingAs($user)
        ->get(route('dashboard'));

    $response->assertStatus(200);
});
