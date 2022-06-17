<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Auth\Subscription;

use Throwable;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Services\SubscriptionService;

class CancelController extends DashboardController
{
    /** @var SubscriptionService */
    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();

        $this->subscriptionService = $subscriptionService;
    }

    public function __invoke()
    {
        try {
            $this->subscriptionService->cancelSubscription($this->user, $this->subscription);

            return redirect()->route('dashboard.plan.upgrade');
        } catch(Throwable $e) {
            AppLogger::error($e, 'store:delete-account');

            return redirect()->back()->with('error', 'Internal server error');
        }
    }
}