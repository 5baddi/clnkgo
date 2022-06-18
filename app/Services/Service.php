<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use BADDIServices\ClnkGO\Domains\FeatureService;

abstract class Service
{
    protected FeatureService $featureService;

    public function __construct()
    {
        $this->featureService = app(FeatureService::class);
    }
}