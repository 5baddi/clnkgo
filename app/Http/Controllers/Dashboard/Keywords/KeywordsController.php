<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Keywords;

use BADDIServices\ClnkGO\App;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Models\AppSetting;
use BADDIServices\ClnkGO\Models\Pack;

class KeywordsController extends DashboardController
{
    public function __invoke()
    {
        $hashtags = AppSetting::where(AppSetting::KEY_COLUMN, App::APP_MOST_USED_KEYWORDS)->first();
        $hashtags = $hashtags instanceof AppSetting ? json_decode($hashtags->value ?? '[]') : [];

        return $this->render('dashboard.keywords', [
            'title'             => 'Your Keywords 🔑',
            'keywords'          => $this->user->getKeywordsAsString(),
            'hashtags'          => $hashtags,
            'max'               => collect($this->pack->features ?? [])->where('key', Pack::KEYWORDS)->first()['limit'] ?? App::MAX_KEYWORDS
        ]);
    }
}