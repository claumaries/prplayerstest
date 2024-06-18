<?php

namespace Tests;

use Exception;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->ensureTestingDatabase();
    }

    /**
     * Ensures that the test environment is using an in-memory SQLite database.
     *
     * @return void
     * @throws Exception If the database configuration is not set correctly for testing.
     */
    protected function ensureTestingDatabase(): void
    {
        $dbConnection = config('database.default');
        $dbName = config("database.connections.{$dbConnection}.database");

        if ($dbConnection !== 'sqlite' || $dbName !== ':memory:') {
            throw new \Exception(
                'Tests are not running on the correct database. Please check your environment configuration.'
            );
        }
    }
}
