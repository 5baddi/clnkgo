<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Keywords;

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Models\Pack;

class KeywordsController extends DashboardController
{
    public function __invoke()
    {
        return view('dashboard.keywords', [
            'title'             => 'Your Keywords 🔑',
            'keywords'          => $this->user->getKeywordsAsString(),
            'hashtags'          => $this->userService->getUsersKeywords(),
            'max'               => collect($this->pack->features)->where('key', Pack::KEYWORDS)->first()['limit'] ?? App::MAX_KEYWORDS
        ]);
    }
}