<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\CPALead;

use Throwable;
use Illuminate\Support\Arr;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use BADDIServices\ClnkGO\AppLogger;
use App\Http\Controllers\Controller;
use BADDIServices\ClnkGO\Domains\CPALeadService;
use BADDIServices\ClnkGO\Jobs\Marketing\TrackCPALead;
use BADDIServices\ClnkGO\Jobs\Marketing\TrackMailingList;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;

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
                $agent = new Agent();
                $userAgent = CPALeadService::DESKTOP_USER_AGENT;

                if ($agent->isAndroidOS()) {
                    $userAgent = CPALeadService::ANDROID_USER_AGENT;
                }

                if ($agent->isPhone()) {
                    $userAgent = CPALeadService::IOS_USER_AGENT;
                }

                $offers = $this->CPALeadService->getCPALeadOffersByGeoAndUserAgent(
                    $request->ip(),
                    $userAgent
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

                    if (is_array($offer) && Arr::has($offer, 'campid', 'link')) {
                        TrackMailingList::dispatch($request->query('email'));
                        TrackCPALead::dispatch($request->query('email'), $offer['campid']);

                        return redirect()->to($offer['link']);
                    }
                }

                TrackMailingList::dispatch($request->query('email'));
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