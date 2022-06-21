<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Requests;

use Illuminate\Http\Response;
use BADDIServices\ClnkGO\Domains\TwitterService;
use App\Http\Requests\Requests\RedirectToTwitterRequest;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

class RedirectToTwitterController extends DashboardController
{
    public function __construct(
        private TwitterService $twitterService
    ) {}

    public function __invoke(RedirectToTwitterRequest $request)
    {
        $profileLink = $this->twitterService->getUserUrl($request->query('username'));

        return redirect()
            ->away(
                $profileLink,
                Response::HTTP_TEMPORARY_REDIRECT
            );
    }
}