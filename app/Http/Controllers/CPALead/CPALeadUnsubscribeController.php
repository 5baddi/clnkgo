<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\CPALead;

use Throwable;
use Illuminate\Http\Request;
use BADDIServices\ClnkGO\AppLogger;
use App\Http\Controllers\Controller;
use BADDIServices\ClnkGO\Models\CPALeadTracking;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;

class CPALeadUnsubscribeController extends Controller
{
    public function __construct(
        private CPALeadTrackingService $CPALeadTrackingService
    ) {}

    public function __invoke(Request $request)
    {
        try {
            if ($request->has('email') && filter_var($request->query('email'), FILTER_VALIDATE_EMAIL)) {
                $existsEmail = $this->CPALeadTrackingService->findByEmail($request->query('email'));
                $data = [CPALeadTracking::EMAIL_COLUMN => $request->query('email')];

                if ($existsEmail instanceof CPALeadTracking) {
                    $data = $existsEmail->toArray();
                }

                $data[CPALeadTracking::IS_UNSUBSCRIBED_COLUMN] = 1;

                $this->CPALeadTrackingService->save($data);
            }
    
        } catch (Throwable $e) {
            AppLogger::error(
                $e,
                'cpalead:unsubscribe', 
                ['payload' => $request->all()]
            );
        }

        return redirect()->route('home');
    }
}