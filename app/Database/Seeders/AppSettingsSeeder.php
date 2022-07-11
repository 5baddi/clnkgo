<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Database\Seeders;

use BADDIServices\ClnkGO\App;
use BADDIServices\ClnkGO\Models\AppSetting;
use BADDIServices\ClnkGO\Services\AppSettingService;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    public function __construct(
        private AppSettingService $appSettingService
    ) {}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->appSettingService->set(AppSetting::EMAILS_PROVIDERS_KEY, App::DEFAULT_EMAIL_PROVIDERS);
        
        $this->appSettingService->set(
            AppSetting::MAIN_HASHTAGS_KEY, 
            array_merge(
                App::DEFAULT_MAIN_HASHTAGS,
                [
                    'helpareporter',
                    'journo'
                ]
            )
        );
    }
}