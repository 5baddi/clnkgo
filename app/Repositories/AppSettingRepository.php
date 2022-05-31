<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\AppSetting;

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
        $parsedValue = $value;
        $type = "string";

        if (is_array($value) || is_object($value)) {
            $parsedValue = json_encode($value);
        }

        if (is_array($value)) {
            $type = AppSetting::ARRAY_TYPE;
        }
        
        if (is_object($value)) {
            $type = AppSetting::OBJECT_TYPE;
        }

        return AppSetting::query()
            ->updateOrCreate(
                [AppSetting::KEY_COLUMN => $key],
                [
                    AppSetting::TYPE_COLUMN     => $type,
                    AppSetting::VALUE_COLUMN    => $parsedValue
                ]
            );
    }
}