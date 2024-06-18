<?php

namespace Events;

use App\Events\UserSaved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserSavedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function is_dispatched_the_user_save_event(): void
    {
        // Arrangements
        Event::fake([
            UserSaved::class,
        ]);
        $admin = User::factory()->create();
        $user  = User::factory()->create();

        $updatedUserData = [
            'prefixname'    => 'Mr',
            'firstname'     => 'John',
            'middlename'    => 'A',
            'lastname'      => 'Doe',
            'suffixname'    => 'Jr',
            'username'      => 'johndoe',
            'email'         => 'john.doe@example.com',
            'redirectRoute' => 'users.index',
        ];

        // Actions
        $response = $this->actingAs($admin)->put(
            route('users.update', ['id' => $user->id]),
            $updatedUserData
        );

        // Assertions
        $response->assertStatus(302);
        Event::assertDispatched(UserSaved::class);
    }
}
