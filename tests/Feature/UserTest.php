<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(function () {
    $this->seed();
});

//uses(RefreshDatabase::class);

it('admin go to users page', function () {
    $user = \App\Models\User::find(1);

    $this->assertDatabaseHas('users', [
        'email' => 'superadmin@luxeplus.com',
        'name' => 'LuxePlus',
    ]);

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertStatus(200);
});

it('user cant go to users page', function () {
    $user = \App\Models\User::find(4);

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertStatus(403);
});

it('admin go to show user', function () {
    $user = \App\Models\User::find(1);

    $this->assertDatabaseHas('users', [
        'email' => 'superadmin@luxeplus.com',
        'name' => 'LuxePlus',
    ]);

    $this->assertTrue($user->hasRole('super-admin'));

    $this->assertDatabaseHas('users', [
        'name' => 'Oscar Rodriguez',
        'email' => 'oscar@luexplus.com',
    ]);

    $response = $this->actingAs($user)->get(route('users.show', $user));

    $response->assertStatus(200);
});

it('user cant go to show user', function () {
    $user = \App\Models\User::find(5);
    $response = $this->actingAs($user)->get(route('users.show', $user));
    $response->assertStatus(403);
});

it('admin can update any user', function () {
    $user = \App\Models\User::find(1);

    $this->assertTrue($user->hasRole('super-admin'));
    $this->assertDatabaseHas('users', [
        'id' => 5,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch(route('users.update', 5), [
            'name' => 'Test User',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => 5,
        'name' => 'Test User',
    ]);

    $response->assertRedirect();
});
