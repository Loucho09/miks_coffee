<?php

it('returns a successful response', function () {
    $response = get('/');

    $response->assertStatus(200);
});
