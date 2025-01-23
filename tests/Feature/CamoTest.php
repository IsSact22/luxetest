<?php

beforeEach(function () {
    $this->seed();
});

it('cam user list camos', function () {
    $user = \App\Models\User::query()->find(3);

    $response = $this
        ->actingAs($user)
        ->get(route('camos.index'));

    $response->assertStatus(200);
});

it('cam has camo', static function () {
    $user = \App\Models\User::query()->find(3);
});
