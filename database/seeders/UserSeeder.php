<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'test@test.com',
            ],
            [
                'prefixname' => 'Mr',
                'firstname'  => 'Test',
                'lastname'   => 'User',
                'username'   => 'testuser',
                'email'      => 'test@test.com',
                'password'   => Hash::make('testPassword24!'),
            ]
        );
    }
}
