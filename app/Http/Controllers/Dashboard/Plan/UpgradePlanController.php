<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Plan;

use BADDIServices\ClnkGO\App;
use BADDIServices\ClnkGO\Entities\Alert;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use BADDIServices\ClnkGO\Services\PackService;

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
            'paypalClientId'        => $this->appSettingService->get(App::PAYPAL_CLIENT_ID, config('paypal.client_id')),
            'paypalPlanId'          => $this->appSettingService->get(App::PAYPAL_PLAN_ID, config('paypal.plan_id'))
        ]);
    }
}