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

    protected AppSettingService $appSettingService;

    public function __construct()
    {
        $this->featureService = app(FeatureService::class);
        $this->appSettingService = app(AppSettingService::class);
    }

    // public function appSetting(): ?AppSettingService
    // {
    //     return $this->appSettingService;
    // }
    
    // public function features(): ?FeatureService
    // {
    //     return $this->featureService;
    // }
}