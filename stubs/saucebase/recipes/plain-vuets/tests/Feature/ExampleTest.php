<?php

it('returns a successful response for the Module URL', function () {
    $response = $this->get('/{module-}');

    $response->assertStatus(200);
});
