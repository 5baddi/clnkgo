<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Database\Seeders;

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Models\AppSetting;
use BADDIServices\SourceeApp\Services\AppSettingService;
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
        $this->appSettingService->set(AppSetting::EMAILS_PROVIDERS_KEY, App::EMAIL_PROVIDERS);
    }
}