<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

use App\Models\User;
use BADDIServices\SourceeApp\Models\Pack;
use Illuminate\Auth\AuthManager;
use BADDIServices\SourceeApp\Models\Subscription;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Services\AppSettingService;
use Illuminate\Support\Arr;

class FeatureService
{
    public function __construct(
        public AppSettingService $appSettingService,
        private AuthManager $authManager,
        private UserService $userService
    ) {}

    public function isEnabled(string $featureName): bool
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

            $allowedUsers = explode($forSetting ?? '', ',');

            return in_array($user->getId() ?? null, array_values($allowedUsers));
        }

        return true;
    }

    public function isPackFeatureEnabled(string $key): bool
    {
        /** @var User|null */
        $user = $this->authManager->user();

        if (! $user instanceof User) {
            return false;
        }

        /** @var Subscription|null */
        $subscription = $user->subscription;

        if (! $subscription instanceof Subscription) {
            return false;
        }

        $subscription->load('pack');

        /** @var Pack|null */
        $pack = $subscription->pack;

        if (! $pack instanceof Pack) {
            return false;
        }

        $feature = collect($pack->features ?? [])
            ->where('key', $key)
            ->first();

        if (! is_array($feature) || ! Arr::has($feature, 'enabled')) {
            return false;
        }
        return $feature['enabled'] ?? false;
    }
}