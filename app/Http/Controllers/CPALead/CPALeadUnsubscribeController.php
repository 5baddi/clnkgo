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
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;

class CPALeadUnsubscribeController extends Controller
{
    public function __construct(
        private CPALeadTrackingService $CPALeadTrackingService
    ) {}

    public function __invoke(Request $request)
    {
        try {
            if (
                $request->has(['email', 'campid']) 
                && filter_var($request->query('email'), FILTER_VALIDATE_EMAIL)
                && filter_var($request->query('campid'), FILTER_VALIDATE_INT)
            ) {
                $this->CPALeadTrackingService->save([
                    CPALeadTracking::CAMPAIGN_ID_COLUMN     => $request->query('campid'),
                    CPALeadTracking::EMAIL_COLUMN           => $request->query('email'),
                    CPALeadTracking::IS_UNSUBSCRIBED_COLUMN => 1
                ]);

                // TODO: use service
                MailingList::query()
                    ->updateOrCreate(
                        [
                            MailingList::EMAIL_COLUMN           => $request->query('email'),
                        ],
                        [
                            MailingList::IS_UNSUBSCRIBED_COLUMN => 1
                        ]
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