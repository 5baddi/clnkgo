<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests;

use Illuminate\Http\Response;
use App\Http\Requests\Requests\RedirectToWebsiteRequest;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class RedirectToJournalistWebsiteController extends DashboardController
{   
    public function __invoke(RedirectToWebsiteRequest $request)
    {
        $url = $request->query('url', '/');

        if (! preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = sprintf('http://%s', $url);
        }
dd($url);
        return redirect()
            ->to($url, Response::HTTP_TEMPORARY_REDIRECT);
    }
}