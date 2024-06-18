<?php

namespace Tests\Unit\Event;

use App\Events\UserSaved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserSavedEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers UserSaved
     *
     * @return void
     */
    public function it_can_create_user_saved_event()
    {
        // Arrange
        $user = User::factory()->unverified()->create();

        // Act
        $event = new UserSaved($user);

        // Assert
        $this->assertInstanceOf(UserSaved::class, $event);
        $this->assertSame($user, $event->user);
    }

    /**
     * @test
     * @covers UserSaved
     *
     * @return void
     */
    public function it_dispatches_user_saved_event()
    {
        // Arrange
        Event::fake();
        $user = User::factory()->unverified()->create();

        // Act
        event(new UserSaved($user));

        // Assert
        Event::assertDispatched(UserSaved::class, function ($event) use ($user) {
            return $event->user->is($user);
        });
    }
}
