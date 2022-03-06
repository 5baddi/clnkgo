<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Models\AppSetting;
use BADDIServices\SourceeApp\Repositories\AppRepository;

class AppService extends Service
{
    /** @var AppRepository */
    private $appRepository;

    public function __construct(AppRepository $appRepository)
    {
        $this->appRepository = $appRepository;
    }

    public function settings(): ?AppSetting
    {
        return $this->appRepository->first();
    }
    
    public function update(array $attributes): bool
    {
        $filteredAttributes = collect($attributes);
        $filteredAttributes = $filteredAttributes->only([
            AppSetting::INSTAGRAM_USERNAME_COLUMN,
            AppSetting::TWITTER_USERNAME_COLUMN,
            AppSetting::FACEBOOK_USERNAME_COLUMN,
            AppSetting::SUPPORT_EMAIL_COLUMN,
        ]);

        return $this->appRepository->update($filteredAttributes->toArray());
    }
}