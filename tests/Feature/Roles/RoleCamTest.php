<?php

it('has rolecam page', function () {
    $response = $this->get('/rolecam');

    $response->assertStatus(200);
});
