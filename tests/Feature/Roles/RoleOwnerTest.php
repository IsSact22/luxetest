<?php

it('has roleowner page', function () {
    $response = $this->get('/roleowner');

    $response->assertStatus(200);
});
