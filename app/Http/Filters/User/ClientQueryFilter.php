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
    public function role(?string $role = null) 
    {
        if (is_null($role)) {
            return;
        }

        $this->builder
            ->where(User::IS_SUPERADMIN_COLUMN, false)
            ->where(User::ROLE_COLUMN, $role)
            ->whereNotNull(User::ROLE_COLUMN);
    }
}