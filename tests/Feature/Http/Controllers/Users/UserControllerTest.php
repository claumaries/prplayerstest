<?php

namespace Tests\Feature\Http\Controllers\Users;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers UserController::list
     *
     * @return void
     */
    public function user_list_screen_can_be_rendered(): void
    {
        // Arrangements
        $user = User::factory()->unverified()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.index'));

        // Assertions
        $response->assertStatus(200);
    }

    /**
     * @test
     * @covers UserController::create
     *
     * @return void
     */
    public function user_create_screen_can_be_rendered(): void
    {
        // Arrangements
        $user = User::factory()->unverified()->create();

        // Actions
        $response = $this->actingAs($user)->get(
            route('users.create', ['id' => intval($user->id)])
        );

        // Assertions
        $response->assertStatus(200);
    }

    /**
     * @test
     * @covers UserController::store
     *
     * @return void
     */
    public function it_can_store_a_new_user(): void
    {
        // Arrangements
        $admin = User::factory()->unverified()->create();
        Storage::fake('public');

        $userData = User::factory()->unverified()->create();

        // Actions
        $this->actingAs($admin)->post(
            route('users.store'),
            array_merge(
                $userData->toArray(),
                ['redirectRoute' => 'users.index']
            )
        );

        // Assertions
        $this->assertDatabaseHas('users', [
            'firstname' => $userData->firstname,
            'email'     => $userData->email,
        ]);
    }

    /**
     * @test
     * @covers UserController::show
     *
     * @return void
     */
    public function user_show_screen_can_be_rendered(): void
    {
        // Arrangements
        $user = User::factory()->unverified()->create();

        // Actions
        $response = $this->actingAs($user)->get(
            route('users.show', ['id' => intval($user->id)])
        );

        // Assertions
        $response->assertStatus(200);
    }

    /**
     * @test
     * @covers UserController::edit
     *
     * @return void
     */
    public function user_edit_screen_can_be_rendered(): void
    {
        // Arrangements
        $user = User::factory()->unverified()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.edit', ['id' => $user->id]));

        // Assertions
        $response->assertStatus(200);
    }

    /**
     * @test
     * @covers UserController::update
     *
     * @return void
     */
    public function it_can_update_a_user(): void
    {
        // Arrangements
        $admin = User::factory()->create();
        $user  = User::factory()->create();

        $updatedUserData = [
            'prefixname' => 'Mr',
            'firstname'  => 'John',
            'middlename' => 'A',
            'lastname'   => 'Doe',
            'suffixname' => 'Jr',
            'username'   => 'johndoe',
            'email'      => 'john.doe@example.com',
        ];

        // Actions
       $this->actingAs($admin)->put(
            route('users.update', ['id' => $user->id]),
            $updatedUserData
        );

        // Assertions
        $this->assertDatabaseHas('users', [
            'id'         => $user->id,
            'prefixname' => 'Mr',
            'firstname'  => 'John',
            'middlename' => 'A',
            'lastname'   => 'Doe',
            'suffixname' => 'Jr',
            'username'   => 'johndoe',
            'email'      => 'john.doe@example.com',
        ]);
    }

    /**
     * @test
     * @covers UserController::destroy
     *
     * @return void
     */
    public function it_can_destroy_a_user(): void
    {
        // Arrangements
        $user     = User::factory()->unverified()->create();
        $userData = User::factory()->unverified()->create();

        // Actions
        $this->actingAs($user)->delete(route('users.destroy', ['userId' => $userData->id]));

        // Assertions
        $this->assertSoftDeleted('users', ['id' => $userData->id]);
    }

    /**
     * @test
     * @covers UserController::trashed
     *
     * @return void
     */
    public function user_trashed_list_screen_can_be_rendered(): void
    {
        // Arrangements
        $user = User::factory()->unverified()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.trashed'));

        // Assertions
        $response->assertStatus(200);
    }

    /**
     * @test
     * @covers UserController::restore
     *
     * @return void
     */
    public function it_can_restore_a_user():void
    {
        // Arrangements
        $admin = User::factory()->create();
        $user  = User::factory()->create(['deleted_at' => now()->subDay()]);

        // Actions
        $this->actingAs($admin)->patch(route('users.restore'), [
            'userId' => $user->id,
        ]);

        // Assertions
        $this->assertDatabaseHas('users', [
            'id'         => $user->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     * @covers UserController::forceDelete
     *
     * @return void
     */
    public function it_can_force_delete_a_user(): void
    {
        // Arrangements
        $admin = User::factory()->create();
        $user  = User::factory()->create(['deleted_at' => now()->subDay()]);

        // Verify user is soft deleted
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        // Actions
        $this->actingAs($admin)->delete(route('users.delete'), [
            'userId' => $user->id,
        ]);

        // Assertions
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
