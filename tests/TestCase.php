<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'tenancy.bootstrappers' => [],
            'tenancy.features'      => [],
            'tenancy.migration_parameters' => [],
        ]);
    }
}