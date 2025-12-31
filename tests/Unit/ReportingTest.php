<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportingTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function intentional_failure_for_testing_ci_reporting()
    {
        // This will fail to test the reporting
        $this->assertEquals(3, 1 + 1, 'Math should work, but this is intentionally wrong');
    }
}
