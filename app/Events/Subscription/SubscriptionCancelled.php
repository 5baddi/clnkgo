<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Events\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SubscriptionCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    public function __construct(
        public string $userId,
        public string $subscriptionId
    ) {}
}