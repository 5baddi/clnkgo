<?php

/**
 * Presspitch.io
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
use BADDIServices\SourceeApp\Models\RequestAnswer;

class AnswerMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    /** @var string */
    public $email;
    
    /** @var User */
    public $user;
    
    /** @var Tweet */
    public $tweet;
    
    /** @var RequestAnswer */
    public $answer;

    /** @var string|null */
    public $from;

    public function __construct(string $email, User $user, Tweet $tweet, RequestAnswer $answer, ?string $from = null)
    {
        $this->email = $email;
        $this->user = $user;
        $this->tweet = $tweet;
        $this->answer = $answer;
        $this->from = $from;
    }

    /**
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}