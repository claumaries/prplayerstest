<?php

namespace Tests\Unit\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CreateDetailsTableTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_users_table_correctly(): void
    {
        // Arrangements
        $columns = [
            'id',
            'user_id',
            'key',
            'value',
            'icon',
            'status',
            'type',
            'created_at',
            'updated_at',
        ];

        // Actions
        $this->artisan('migrate');

        // Assertions
        $this->assertTrue(Schema::hasTable('details'));
        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('details', $column));
        }
    }

    /**
     * @test
     */
    public function it_drops_the_user_tables_correctly(): void
    {
        // Arrangements
        $this->artisan('migrate');

        // Actions
        $this->artisan('migrate:rollback');

        // Assertions
        $this->assertFalse(Schema::hasTable('details'));
    }
}
