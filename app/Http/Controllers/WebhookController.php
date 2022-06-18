<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use BADDIServices\ClnkGO\Domains\FeatureService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WebhookController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var FeatureService */
    protected $featureService;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var FeatureService */
            $this->featureService = app(FeatureService::class);

            return $next($request);
        });
    }
}