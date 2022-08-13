<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\CPALead;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use BADDIServices\ClnkGO\Models\CPALeadTracking;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;

class CPALeadUnsubscribeController extends Controller
{
    public function __construct(
        private CPALeadTrackingService $CPALeadTrackingService
    ) {
        parent::__construct();
    }

    public function __invoke(Request $request)
    {
        if ($request->has('email') && filter_var($request->query('email'), FILTER_VALIDATE_EMAIL)) {
            $this->CPALeadTrackingService->save([
                CPALeadTracking::EMAIL_COLUMN           => $request->query('email'),
                CPALeadTracking::IS_UNSUBSCRIBED_COLUMN => 1,
            ]);
        }

        return redirect()->route('home');
    }
}