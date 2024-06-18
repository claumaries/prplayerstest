<?php

namespace Listeners;

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SaveUserBackgroundInformationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function user_saved_event_triggers_listener(): void
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
        Event::assertListening(
            UserSaved::class,
            SaveUserBackgroundInformation::class
        );
    }
}
