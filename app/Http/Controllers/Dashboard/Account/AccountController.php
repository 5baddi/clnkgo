<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Account;

use Illuminate\Http\Request;
use BADDIServices\SourceeApp\Services\PackService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class AccountController extends DashboardController
{
    /** @var PackService */
    private $packService;

    public function __construct(PackService $packService)
    {
        parent::__construct();

        $this->packService = $packService;
    }
    
    public function __invoke(Request $request)
    {
        return view('dashboard.account', [
            'title'         =>  'Account',
            'tab'           =>  $request->query('tab', 'settings'),
            'user'          =>  $this->user,
            'currentPack'   =>  $this->packService->loadCurrentPack($this->user)
        ]);
    }
}