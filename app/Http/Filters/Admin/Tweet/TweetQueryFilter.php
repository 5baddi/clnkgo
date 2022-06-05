<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Filters\Admin\Tweet;

use BADDIServices\SourceeApp\Http\Filters\QueryFilter;
use BADDIServices\SourceeApp\Models\Tweet;

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