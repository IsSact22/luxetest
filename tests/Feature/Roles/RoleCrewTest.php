<?php

it('has rolecrew page', function () {
    $response = $this->get('/rolecrew');

    $response->assertStatus(200);
});
