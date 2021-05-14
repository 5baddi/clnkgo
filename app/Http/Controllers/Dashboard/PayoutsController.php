<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class PayoutsController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.payouts', [
            'title'     =>  'Payouts'
        ]);
    }
}