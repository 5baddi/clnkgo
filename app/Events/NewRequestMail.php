<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Events;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use BADDIServices\SourceeApp\Models\Tweet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewRequestMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    /** @var User */
    public $user;
    
    /** @var Tweet */
    public $tweet;


    public function __construct(User $user, Tweet $tweet)
    {
        $this->user = $user;
        $this->tweet = $tweet;
    }

    /**
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}