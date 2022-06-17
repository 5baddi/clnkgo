<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Events\LinkedEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class LinkedEmailConfirmationMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    public function __construct(public string $linkedEmailId) {}
}
