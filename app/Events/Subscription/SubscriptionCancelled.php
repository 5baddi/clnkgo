<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Events\Subscription;

use App\Models\User;
use BADDIServices\SourceeApp\Models\Subscription;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;

class SubscriptionCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;
    
    /** @var User */
    public $user;

    /** @var Subscription */
    public $subscription;

    public function __construct(User $user, Subscription $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
    }

    /**
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}