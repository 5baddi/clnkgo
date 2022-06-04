<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Filters\User;

use App\Models\User;
use BADDIServices\SourceeApp\Http\Filters\QueryFilter;

class ClientQueryFilter extends QueryFilter
{
    public function roles(?array $roles = null) 
    {
        if (is_null($roles) || count($roles) === 0) {
            return;
        }

        $this->builder
            ->whereIn(User::ROLE_COLUMN, $roles);
    }
}