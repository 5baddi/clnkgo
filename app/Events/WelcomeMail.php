<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use BADDIServices\SocialRocket\Models\Store;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class WelcomeMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Store */
    public $store;
    
    /** @var User */
    public $user;

    public function __construct(Store $store, User $user)
    {
        $this->store = $store;
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
