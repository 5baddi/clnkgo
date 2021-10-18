<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Database\Seeders;

use BADDIServices\SocialRocket\Common\Entities\Subscription\Feature;
use BADDIServices\SocialRocket\Common\Services\Subscription\PackFeatureService;
use Illuminate\Database\Seeder;

class FeaturesSeeder extends Seeder
{
    /** @var PackFeatureService */
    private $packFeatureService;

    public function __construct(PackFeatureService $packFeatureService)
    {
        $this->packFeatureService = $packFeatureService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->packFeatureService->bulkCreate([
            [
                Feature::KEY_COLUMN       =>  Feature::UNLIMITED_AFFILIATES,
                Feature::NAME_KEY_COLUMN  =>  'unlimited_affiliates',
            ],
            [
                Feature::KEY_COLUMN       =>  Feature::REPORTING,
                Feature::NAME_KEY_COLUMN  =>  'reporting',
            ],
            [
                Feature::KEY_COLUMN       =>  Feature::PAYOUT_METHODS,
                Feature::NAME_KEY_COLUMN  =>  'payout_methods',
            ],
            [
                Feature::KEY_COLUMN       =>  Feature::SUPPORT,
                Feature::NAME_KEY_COLUMN  =>  'support',
            ],
            [
                Feature::KEY_COLUMN       =>  Feature::CUSTOMIZATION,
                Feature::NAME_KEY_COLUMN  =>  'customization',
            ],
            [
                Feature::KEY_COLUMN       =>  Feature::REVENUE_NOT_SHARED,
                Feature::NAME_KEY_COLUMN  =>  'revenue_not_shared',
            ],
        ]);
    }
}
