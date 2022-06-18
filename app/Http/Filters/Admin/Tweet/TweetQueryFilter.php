<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Filters\Admin\Tweet;

use BADDIServices\ClnkGO\Http\Filters\QueryFilter;
use BADDIServices\ClnkGO\Models\Tweet;

class TweetQueryFilter extends QueryFilter
{
    public function source(?string $source = null) 
    {
        if (is_null($source)) {
            return;
        }

        $this->builder
            ->where(Tweet::SOURCE_COLUMN, $source);
    }
}