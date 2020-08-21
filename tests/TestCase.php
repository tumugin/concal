<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setupPassport(): void
    {
        $this->artisan('passport:client --personal --no-interaction --name=test_client');
    }
}
