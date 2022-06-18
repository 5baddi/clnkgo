<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Account;

use Illuminate\Http\Request;
use BADDIServices\ClnkGO\Services\PackService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

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
        return $this->render('dashboard.account.index', [
            'title'         => 'Account',
            'tab'           => $request->query('tab', 'settings'),
            'user'          => $this->user,
            'emails'        => implode(',', $this->user->linkedEmails->pluck('email')->toArray() ?? []),
            'currentPack'   => $this->packService->loadCurrentPack($this->user)
        ]);
    }
}