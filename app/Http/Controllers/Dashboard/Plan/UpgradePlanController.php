<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Plan;

use BADDIServices\SourceeApp\App;
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
            'paypalClientId'        => config('paypal.client_id')//$this->userService->appSetting()->get(App::PAYPAL_CLIENT_ID, config('paypal.client_id'))
        ]);
    }
}