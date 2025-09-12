<?php

uses(Tests\TestCase::class);

it('returns a successful response for the Module URL', function () {
    $response = $this->get('/locale/en');

    $response->assertStatus(200);
});
