<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can get all users', function () {
    $users = \App\Models\User::factory()->count(10)->create();
    $this->assertDatabaseCount('users', 10);
});

it('model User can be created', function () {
    $user = \App\Models\User::factory()->create();
    $this->assertDatabaseCount('users', 1);
});

it('model user can be updated', function () {
    $user = \App\Models\User::factory()->create([
        'name' => 'User Test',
    ]);

    $user->update(['name' => 'User Testing']);

    $this->assertDatabaseHas('users', [
        'name' => 'User Testing',
    ]);

});

it('model user can be destroy', function () {
    $user = \App\Models\User::factory()->create([
        'name' => 'User Test',
    ]);

    $user->delete();

    $this->assertDatabaseMissing('users', [
        'name' => 'User Test',
    ]);
});
