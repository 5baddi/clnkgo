<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

use App\Models\User;
use BADDIServices\SourceeApp\Services\AppSettingService;
use BADDIServices\SourceeApp\Services\UserService;
use Illuminate\Auth\AuthManager;

class FeatureService
{
    public function __construct(
        private AppSettingService $appSettingService,
        private AuthManager $authManager,
        private UserService $userService
    ) {}

    public function isEnabled(string $featureName)
    {
        $isEnabledConfig = config(sprintf('features.%s.enabled', $featureName), false);
        $forConfig = config(sprintf('features.%s.for', $featureName), '');

        $isEnabledSetting = $this->appSettingService->get(sprintf('features.%s.enabled', $featureName), $isEnabledConfig);
        $forSetting = $this->appSettingService->get(sprintf('features.%s.for', $featureName), $forConfig);

        if (! $isEnabledSetting) {
            return false;
        }

        if (! empty($forSetting)) {
            /** @var User|null */
            $user = $this->authManager->user();

            $allowedUsers = explode($forSetting, ',');

            return in_array($user->getId(), array_values($allowedUsers));
        }

        return true;
    }
}