<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Exceptions\Shopify;

use Exception;
use Throwable;

class FetchResourcesFailed extends Exception
{
    /** @var int */
    public const CODE = 63;

    /** @var string */
    public const MESSAGE = "Failed to fetch resources";

    public function __construct(string $message = self::MESSAGE, int $code = self::CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}