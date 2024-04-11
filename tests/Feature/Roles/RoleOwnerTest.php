<?php

use App\Models\User;

beforeEach(function () {
    $this->seed();
});

it('has role owner', function () {
    $user = User::find(5);
    $this->assertTrue($user->hasRole('owner'));
    $response = $this
        ->actingAs($user)
        ->get(route('dashboard'));

    $response->assertStatus(200);
});
