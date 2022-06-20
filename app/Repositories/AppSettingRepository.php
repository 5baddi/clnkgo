<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Repositories;

use BADDIServices\ClnkGO\Models\AppSetting;

class AppSettingRepository
{
    public function get(string $key): ?AppSetting
    {
        return AppSetting::query()
            ->where(AppSetting::KEY_COLUMN, $key)
            ->first();
    }

    public function set(string $key, int $type, mixed $value): ?AppSetting
    {
        return AppSetting::query()
            ->updateOrCreate(
                [AppSetting::KEY_COLUMN => $key],
                [
                    AppSetting::TYPE_COLUMN     => $type,
                    AppSetting::VALUE_COLUMN    => $value
                ]
            );
    }
}