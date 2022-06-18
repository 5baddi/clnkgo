<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO;

use App\Models\User;
use Throwable;
use Bugsnag\Client;
use Bugsnag\Configuration;
use Bugsnag\Breadcrumbs\Breadcrumb;
use Illuminate\Support\Facades\Log;

class AppLogger
{
    /** @var User|null */
    private static $user = null;

    /** @var AppLogger */
    private static $instance = null;

    /** @var Client */
    private static $client = null;

    private function __construct() { }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;

            self::$client = new Client(
                new Configuration(env('BUGSNAG_API_KEY'))
            );

            self::$client->setAppVersion(config('baddi.version'));
        }

        return self::$instance;
    }


    public static function setUser(?User $user = null): self
    {
        self::$user = $user;

        return self::getInstance();
    }

    public static function error(Throwable $exception, string $context, array $extra = [])
    {
        Log::error($exception->getMessage(), [
            'context'   =>  $context,
            'user'      =>  optional(self::$user)->id,
            'code'      =>  $exception->getCode(),
            'line'      =>  $exception->getLine(),
            'file'      =>  $exception->getFile(),
            'trace'     =>  $exception->getTraceAsString(),
            'extra'     =>  json_encode($extra)
        ]);

        if (! self::$client) {
            self::getInstance();
        }

        self::$client->notifyException($exception);
    }
    
    public static function info(string $message, string $context, array $extra = [])
    {
        $infoContext = [
            'context'   =>  $context,
            'user'      =>  optional(self::$user)->id,
            'extra'     =>  json_encode($extra)
        ];

        Log::info($message, $infoContext);

        self::$client->leaveBreadcrumb(
            $message,
            Breadcrumb::LOG_TYPE,
            $infoContext
        );
    }
}