<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription;

use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class CancelCheckoutController extends DashboardController
{
    public function __invoke()
    {
        return redirect()
            ->route('dashboard.plan.upgrade')
            ->with(
                'alert',
                new Alert('Choose a plan around then come back to pay!')
            );
    }
}
