<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Models\AppSetting;
use BADDIServices\SourceeApp\Repositories\AppSettingRepository;

class AppSettingService extends Service
{
    public function __construct(
        private AppSettingRepository $appSettingRepository
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $appSetting = $this->appSettingRepository->get($key);
        if (! $appSetting instanceof AppSetting) {
            return $default;
        }

        return $appSetting;
    }

    public function set(string $key, mixed $value): ?AppSetting
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

        return $this->appSettingRepository->set($key, $type, $parsedValue);
    }
}