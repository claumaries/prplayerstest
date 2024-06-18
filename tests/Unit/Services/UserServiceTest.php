<?php

namespace Tests\Unit\Services;

use App\Enums\PrefixEnums;
use App\Models\Detail;
use App\Models\User;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private User $model;

    /**
     * @var UserService
     */
    private UserServiceInterface $userService;

    /**
     * @test
     * @covers UserService::rules
     *
     * @return void
     */
    public function it_provides_rules_for_updating_a_user(): void
    {
        // Arrangements
        $user          = $this->model::factory()->unverified()->create();
        $expectedRules = [
            'prefixname' => ['required', Rule::in(PrefixEnums::values())],
            'firstname'  => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname'   => ['required', 'string', 'max:255'],
            'suffixname' => ['nullable', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id . ',id'],
            'photo'      => ['nullable', 'mimes:jpg,png,jpeg,gif,svg'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id . ',id'],
        ];


        // Actions
        $rules = $this->userService->rules($user->id);


        // Assertions
        $this->assertEquals($expectedRules, $rules);
    }

    /**
     * @test
     * @covers UserService::list
     */
    public function it_can_return_a_paginated_list_of_users(): void
    {
        // Arrangements
        $this->model::factory(25)->unverified()->create();

        // Actions
        $result = $this->userService->list();

        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(25, $result->total());
        $this->assertCount(10, $result);
    }

    /**
     * @test
     * @covers UserService::store
     */
    public function it_can_store_a_user_to_database(): void
    {
        // Arrangements
        $userAttributes = [
            'prefixname'            => 'Mr',
            'firstname'             => 'John',
            'middlename'            => 'Quincy',
            'lastname'              => 'Doe',
            'suffixname'            => 'Jr.',
            'username'              => 'johnqdoe',
            'photo'                 => null,
            'email'                 => 'john.doe@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Actions
        $createdUser = $this->userService->store($userAttributes);

        // Assertions
        $this->assertDatabaseHas('users', [
            'prefixname' => 'Mr',
            'firstname'  => 'John',
            'middlename' => 'Quincy',
            'lastname'   => 'Doe',
            'suffixname' => 'Jr.',
            'username'   => 'johnqdoe',
            'photo'      => null,
            'email'      => 'john.doe@example.com',
        ]);

        $this->assertEquals('John', $createdUser->firstname);
        $this->assertEquals('john.doe@example.com', $createdUser->email);
    }

    /**
     * @test
     * @covers UserService::update
     */
    public function it_can_update_an_existing_user(): void
    {
        // Arrangements
        $user              = $this->model::factory()->unverified()->create();
        $updatedAttributes = [
            'firstname' => 'Test Firstname',
            'email'     => 'test@example.com',
        ];

        // Actions
        $result = $this->userService->update($user->id, $updatedAttributes);

        // Assertions
        $this->assertTrue($result);

        $this->assertDatabaseHas('users', [
            'id'        => $user->id,
            'firstname' => 'Test Firstname',
            'email'     => 'test@example.com',
        ]);
    }

    /**
     * @test
     * @covers UserService::find
     */
    public function it_can_find_and_return_an_existing_user(): void
    {
        // Arrangements
        $user = $this->model::factory()->unverified()->create([
            'firstname' => 'John',
            'email'     => 'john.doe@example.com',
        ]);

        // Actions
        $foundUser = $this->userService->find($user->id);

        // Assertions
        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals('John', $foundUser->firstname);
        $this->assertEquals('john.doe@example.com', $foundUser->email);
    }

    /**
     * @test
     * @covers UserService::destroy
     */
    public function it_can_soft_delete_an_existing_user(): void
    {
        // Arrangements
        $user = $this->model::factory()->unverified()->create();

        // Actions
        $this->userService->destroy($user->id);

        // Assertions
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * @test
     * @covers UserService::delete
     */
    public function it_can_permanently_delete_a_soft_deleted_user(): void
    {
        // Arrangements
        $user = $this->model::factory()->unverified()->create();
        $user->delete();

        // Actions
        $this->userService->delete($user->id);

        // Assertions
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * @test
     * @covers UserService::listTrashed
     */
    public function it_can_return_a_paginated_list_of_trashed_users(): void
    {
        // Arrangements
        $users = $this->model::factory(14)->unverified()->create();
        $users->each(function ($user) {
            $user->delete();
        });

        // Actions
        $trashedUsers = $this->userService->listTrashed();

        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class, $trashedUsers);
        $this->assertEquals(14, $trashedUsers->total());
        $this->assertCount(10, $trashedUsers);

        foreach ($trashedUsers as $user) {
            $this->assertTrue($user->trashed());
        }
    }

    /**
     * @test
     * @covers UserService::restore
     */
    public function it_can_restore_a_soft_deleted_user(): void
    {
        // Arrangements
        $user = $this->model::factory()->unverified()->create();
        $user->delete();

        // Actions
        $this->userService->restore($user->id);

        // Assertions
        $restoredUser = $this->model::find($user->id);
        $this->assertNotNull($restoredUser);
        $this->assertFalse($restoredUser->trashed());
    }

    /**
     * @test
     * @coversHashService::hash
     *
     * @return void
     */
    public function it_can_generate_a_hash_key()
    {
        // Arrangements
        $key = 'testpassword9292!!';

        // Actions
        $hashedKey = $this->userService->hash($key);

        // Assertions
        $this->assertNotEmpty($hashedKey);
        $this->assertTrue(Hash::check($key, $hashedKey));
    }

    /**
     * @test
     * @covers UserService::upload
     */
    public function it_can_upload_photo(): void
    {
        // Arrangements
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');

        // Actions
        $filename = $this->userService->upload($file);

        // Assertions
        $this->assertNotNull($filename);

        Storage::disk('public')->assertExists('avatars/' . $filename);
        $this->assertEquals('jpg', substr($filename, strrpos($filename, '.') + 1));
    }

    /**
     * @test
     * @covers UserService::storeDetails
     *
     * @return void
     */
    public function it_can_store_user_details()
    {
        // Arrangements
        $user = $this->model::factory()->unverified()->create();

        $details = [
            new Detail(['key' => 'Full name', 'value' => 'John Doe']),
            new Detail(['key' => 'Middle Initial', 'value' => 'A']),
        ];

        // Actions
        $this->userService->storeDetails($user->id, $details);

        // Assertions
        $this->assertDatabaseHas('details', [
            'user_id' => $user->id,
            'key' => 'Full name',
            'value' => 'John Doe'
        ]);

        $this->assertDatabaseHas('details', [
            'user_id' => $user->id,
            'key' => 'Middle Initial',
            'value' => 'A'
        ]);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model       = app()->make(User::class);
        $this->userService = app()->make(UserServiceInterface::class);
    }
}
