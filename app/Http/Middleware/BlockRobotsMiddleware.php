<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Middleware;

use Illuminate\Http\Request;
use Spatie\RobotsMiddleware\RobotsMiddleware;

class BlockRobotsMiddleware extends RobotsMiddleware
{   
     /**
     * @return string|bool
     */
    protected function shouldIndex(Request $request)
    {
        return 'none';
    }
}