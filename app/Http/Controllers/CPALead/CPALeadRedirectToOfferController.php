<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\CPALead;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use BADDIServices\ClnkGO\AppLogger;
use App\Http\Controllers\Controller;
use BADDIServices\ClnkGO\Domains\CPALeadService;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;
use Carbon\Carbon;

class CPALeadRedirectToOfferController extends Controller
{
    public function __construct(
        private CPALeadService $CPALeadService,
        private CPALeadTrackingService $CPALeadTrackingService
    ) {}

    public function __invoke(Request $request)
    {
        try {
            if (
                $request->has(['email']) 
                && filter_var($request->query('email'), FILTER_VALIDATE_EMAIL)
            ) {
                $offers = $this->CPALeadService->getCPALeadOffersByGeoAndUserAgent(
                    $request->ip()
                    // FIXME: $request->userAgent()  Android phone, iOS phone, or desktop
                );

                if ($offers->count() > 0) {
                    $offer = $offers
                        ->filter(function (array $offer) {
                            return (
                                Arr::has($offer, ['link', 'campid', 'amount', 'category_name'])
                                && in_array($offer['category_name'], CPALeadService::SUPPORTED_OFFER_TYPES)
                                && floatval($offer['amount'] ?? 0) > 0.25
                            );
                        })
                        ->sortBy('amount', SORT_DESC)
                        ->first();

                    if (is_array($offer) && Arr::has($offer, 'link')) {
                        // TODO: use service
                        MailingList::query()
                            ->updateOrCreate(
                                [
                                    MailingList::EMAIL_COLUMN       => $request->query('email'),
                                ],
                                [
                                    MailingList::IS_ACTIVE_COLUMN   => 1,
                                    MailingList::SENT_AT_COLUMN     => Carbon::now(),
                                ]
                            );
                            
                        CPALeadTracking::query()
                            ->updateOrCreate(
                                [
                                    CPALeadTracking::EMAIL_COLUMN       => $request->query('email'),
                                ],
                                [
                                    CPALeadTracking::CAMPAIGN_ID_COLUMN => $offer['campid'],
                                    CPALeadTracking::SENT_AT_COLUMN     => Carbon::now(),
                                ]
                            );

                        return redirect()->to($offer['link']);
                    }
                }

                MailingList::query()
                    ->updateOrCreate(
                        [
                            MailingList::EMAIL_COLUMN       => $request->query('email'),
                        ],
                        [
                            MailingList::IS_ACTIVE_COLUMN => 1
                        ]
                    );
            }
        } catch (Throwable $e) {
            dd($e);
            AppLogger::error(
                $e,
                'cpalead:redirect-to-offer', 
                ['payload' => $request->all()]
            );
        }

        return redirect()->route('home');
    }
}