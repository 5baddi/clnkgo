<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Keywords;

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Models\AppSetting;
use BADDIServices\SourceeApp\Models\Pack;

class KeywordsController extends DashboardController
{
    public function __invoke()
    {
        $hashtags = AppSetting::where(AppSetting::KEY_COLUMN, App::APP_MOST_USED_KEYWORDS)->first();
        $hashtags = $hashtags instanceof AppSetting ? json_decode($hashtags->value ?? '[]') : [];

        return view('dashboard.keywords', [
            'title'             => 'Your Keywords 🔑',
            'keywords'          => $this->user->getKeywordsAsString(),
            'hashtags'          => $hashtags,
            'max'               => collect($this->pack->features ?? [])->where('key', Pack::KEYWORDS)->first()['limit'] ?? App::MAX_KEYWORDS
        ]);
    }
}