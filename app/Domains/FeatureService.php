<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

use BADDIServices\SourceeApp\Services\Service;

class FeatureService extends Service
{
    public static function isEnabled(string $featureName)
    {
        return config(sprintf('features.%s.enabled', $featureName), false);
    }
}