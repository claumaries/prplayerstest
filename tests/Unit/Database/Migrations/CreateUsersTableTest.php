<?php

namespace Tests\Unit\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CreateUsersTableTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_users_table_correctly(): void
    {
        // Arrangements
        $columns = [
            'id',
            'prefixname',
            'firstname',
            'middlename',
            'lastname',
            'suffixname',
            'username',
            'email',
            'email_verified_at',
            'password',
            'photo',
            'type',
            'remember_token',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        // Actions
        $this->artisan('migrate');

        // Assertions
        $this->assertTrue(Schema::hasTable('users'));
        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('users', $column));
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
        $this->assertFalse(Schema::hasTable('users'));
    }
}
