<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param User $user
     */
    public function __construct(
        public readonly User $user
    ) {}
}
