<?php

use App\Models\User;

beforeEach(function () {
    $this->seed();
});

it('Access Admin Dashboard', function () {
    $user = User::find(2);
    $this->assertTrue($user->hasRole('admin'));
    $response = $this
        ->actingAs($user)
        ->get(route('dashboard'));

    $response->assertStatus(200);
});

it('Access Admin Camos', function () {
    $user = User::find(2);
    $this->assertTrue($user->hasRole('admin'));
    $response = $this
        ->actingAs($user)
        ->get(route('camos.index'));

    $response->assertStatus(200);
});

it('Access Admin Show Camo', function () {
    $user = User::find(2);
    $this->assertTrue($user->hasRole('admin'));
    $this->assertTrue($user->can('read-camo'));

    $response = $this
        ->actingAs($user)
        ->get(route('camos.show', 1));

    $response->assertStatus(200);
});

it('Admin cant Update Camo', function () {
    $user = User::find(2);
    $this->assertTrue($user->hasRole('admin'));
    $response = $this
        ->actingAs($user)
        ->patch(route('camos.update', 1), [
            'customer' => 'Customer Test',
        ]);
    $response->assertStatus(403);
});

it('Admin can view Users', function () {
    $user = User::find(2);
    $this->assertTrue($user->hasRole('admin'));
    $response = $this
        ->actingAs($user)
        ->get(route('users.index'));
    $response->assertStatus(200);
});

it('Admin can view User', function () {
    $user = User::find(2);
    $this->assertTrue($user->hasRole('admin'));
    $response = $this
        ->actingAs($user)
        ->get(route('users.show', 1));
    $response->assertStatus(200);
});
