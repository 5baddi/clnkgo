<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers;

use App\Http\Controllers\Controller;
use BADDIServices\SourceeApp\Services\PackService;

class LandingPageController extends Controller
{
    /** @var PackService */
    private $packService;

    public function __construct(PackService $packService)
    {
        $this->packService = $packService;
    }

    public function __invoke()
    {
        return view('landing', [
            'packs'         =>  $this->packService->all()
        ]);
    }

    public function privacy()
    {
        return view('privacy');
    }
}