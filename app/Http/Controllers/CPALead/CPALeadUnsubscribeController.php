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
use Illuminate\Support\Facades\Event;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Events\Marketing\MailingListEmailWasVerified;

class CPALeadUnsubscribeController extends Controller
{
    public function __construct(
        private CPALeadTrackingService $CPALeadTrackingService
    ) {}

    public function __invoke(Request $request)
    {
        try {
            if (
                $request->has(['email']) 
                && filter_var($request->query('email'), FILTER_VALIDATE_EMAIL)
            ) {
                $this->CPALeadTrackingService->updateOrCreate(
                    [CPALeadTracking::EMAIL_COLUMN => $request->query('email')],
                    [CPALeadTracking::IS_UNSUBSCRIBED_COLUMN => 1]
                );

                Event::dispatch(
                    new MailingListEmailWasVerified($request->query('email'))
                );
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