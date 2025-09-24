<?php

uses(Tests\TestCase::class);

test('returns a successful response for the Module URL', function () {
    $response = $this->get('/settings');

    $response->assertStatus(200);
});
