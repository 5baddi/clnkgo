<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AnswerMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    public function __construct(
        public string $email,
        public string $userId,
        public string $tweetId,
        public string $answerId,
        public ?string $from = null
    ) {}
}