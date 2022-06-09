<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Domains\FeatureService;

abstract class Service
{
    protected FeatureService $featureService;

    public function __construct()
    {
        $this->featureService = app(FeatureService::class);
    }

    public function appSetting(): AppSettingService
    {
        return $this->featureService->appSettingService;
    }
    
    public function features(): FeatureService
    {
        return $this->featureService;
    }
}