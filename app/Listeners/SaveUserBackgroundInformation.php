<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Models\Detail;
use App\Models\User;
use App\Services\UserService;

readonly class SaveUserBackgroundInformation
{
    /**
     * @param UserService $userService
     * @param Detail      $detail
     */
    public function __construct(
        private UserService $userService,
        private Detail      $detail,
    ) {}

    /**
     * Handle the event.
     *
     * @param UserSaved $event
     *
     * @return void
     */
    public function handle(UserSaved $event): void
    {
        $user = $event->user;

        // Call the method in UserService to save user details
        $this->userService->storeDetails(
            $user->id,
            $this->createUserDetails($user)
        );
    }

    /**
     * Create the user details array.
     *
     * @param User $user
     *
     * @return array
     */
    private function createUserDetails(User $user): array
    {
        $details = [
            [
                'key'   => 'Full name',
                'value' => $user->fullname,
                'type'  => 'bio',
            ],
            [
                'key'   => 'Middle Initial',
                'value' => $user->middleinitial,
                'type'  => 'bio',
            ],
            [
                'key'   => 'Avatar',
                'value' => $user->avatar,
                'type'  => 'bio',
            ],
            [
                'key'   => 'Gender',
                'value' => $user->gender,
                'type'  => 'bio',
            ],
        ];

        // Transform array to Detail instances
        return array_map(function ($detail) {
            return new $this->detail($detail);
        }, $details);
    }
}
