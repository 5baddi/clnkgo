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
use BADDIServices\ClnkGO\Models\Marketing\MailingList;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;

class CPALeadRedirectToOfferController extends Controller
{
    public function __construct(
        private CPALeadTrackingService $CPALeadTrackingService
    ) {}

    public function __invoke(Request $request)
    {
        try {
            if (
                $request->has(['email', 'offer']) 
                && filter_var($request->query('email'), FILTER_VALIDATE_EMAIL)
                && filter_var($request->query('offer'), FILTER_VALIDATE_URL)
            ) {

                // TODO: use service
                MailingList::query()
                    ->updateOrCreate(
                        [
                            MailingList::EMAIL_COLUMN       => $request->query('email'),
                        ],
                        [
                            MailingList::IS_ACTIVE_COLUMN => 1
                        ]
                    );

                return redirect()->to($request->query('offer'));
            }
        } catch (Throwable $e) {
            AppLogger::error(
                $e,
                'cpalead:redirect-to-offer', 
                ['payload' => $request->all()]
            );
        }

        return redirect()->route('home');
    }
}