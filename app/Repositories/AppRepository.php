<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\AppSetting;

class AppRepository
{
    public function first(): ?AppSetting
    {
        return AppSetting::query()
                    ->first();
    }
    
    public function update(array $attributes): bool
    {
        return AppSetting::query()
                    ->update($attributes) > 0;
    }
}