<?php

namespace Tests\Unit\Listeners;

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveUserBackgroundInformationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle_saves_details_to_database(): void
    {
        $user     = User::factory()->unverified()->create();
        $listener = app()->make(SaveUserBackgroundInformation::class);


        $event = new UserSaved($user);
        $listener->handle($event);


        $this->assertDatabaseHas('details', [
            'key'     => 'Full name',
            'value'   => $user->fullname,
            'type'    => 'bio',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('details', [
            'key'     => 'Middle Initial',
            'value'   => $user->middleinitial,
            'type'    => 'bio',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('details', [
            'key'     => 'Avatar',
            'value'   => $user->avatar,
            'type'    => 'bio',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('details', [
            'key'     => 'Gender',
            'value'   => $user->gender,
            'type'    => 'bio',
            'user_id' => $user->id,
        ]);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }
}
