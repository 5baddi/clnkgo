<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Plan;

use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use BADDIServices\SourceeApp\Services\PackService;

class UpgradePlanController extends DashboardController
{
    /** @var PackService */
    private $packService;

    public function __construct(PackService $packService)
    {
        parent::__construct();
        
        $this->packService = $packService;
    }

    public function __invoke()
    {
        if ($this->user->isSuperAdmin()) {
            return redirect()
                ->route('admin')
                ->with('alert', new Alert('Don\'t forget you\'re using super admin account!'));
        }

        return $this->render('dashboard.plan.upgrade', [
            'title'                 => 'Upgrade your plan',
            'subscription'          => $this->subscription,
            'isTrial'               => ($this->user->isSuperAdmin() || $this->subscription->isTrial()),
            'packs'                 => $this->packService->all(),
            'currentPack'           => $this->packService->loadCurrentPack(Auth::user()),
            'paypalClientId'        => 'AW_01BLjqvu-K0enhW8bHhjdsVdCbus6BmB_2Iul5ULMrx3Upk2xIIhALr7CDEc4f_MHfsi-wbbGQ7O0'
        ]);
    }
}