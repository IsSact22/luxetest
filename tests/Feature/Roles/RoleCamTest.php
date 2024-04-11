<?php

use App\Models\User;

beforeEach(function () {
    $this->seed();
});

it('has role cam', function () {
    $user = User::find(3);
    $this->assertTrue($user->hasRole('cam'));
    $response = $this
        ->actingAs($user)
        ->get(route('dashboard'));

    $response->assertStatus(200);
});
