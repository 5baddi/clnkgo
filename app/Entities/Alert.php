<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Entities;

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
