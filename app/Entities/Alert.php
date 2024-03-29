<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Entities;

class Alert
{
    /** @var string */
    public $message;
    public $type;

    public function __construct(string $message, string $type = 'error')
    {
        $this->message = $message;
        $this->type = $type;
    }
}
