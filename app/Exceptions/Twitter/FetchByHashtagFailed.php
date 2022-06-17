<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Exceptions\Twitter;

use Exception;
use Throwable;

class FetchByHashtagFailed extends Exception
{
    /** @var int */
    public const CODE = 303;

    /** @var string */
    public const MESSAGE = "Failed to fetch by hashtag";

    public function __construct(string $message = self::MESSAGE, int $code = self::CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}