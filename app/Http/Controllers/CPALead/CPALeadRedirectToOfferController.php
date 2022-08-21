<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\CPALead;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use BADDIServices\ClnkGO\AppLogger;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use BADDIServices\ClnkGO\Domains\CPALeadService;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMailWasViewed;
use BADDIServices\ClnkGO\Events\Marketing\MailingListEmailWasVerified;

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
                Event::dispatch(
                    new MailingListEmailWasVerified($request->query('email'))
                );

                $offers = $this->CPALeadService->getCPALeadOffersByGeoAndUserAgent(
                    $request->ip(),
                    $request->header('User-Agent')
                );
                if ($request->query('email') === 'life5baddi@gmail.com') {
                    dd($offers);
                }
                if ($offers->count() > 0) {
                    $offer = $offers
                        ->filter(function (array $offer) {
                            return (
                                Arr::has($offer, ['link', 'campid', 'amount', 'category_name'])
                                && floatval($offer['amount'] ?? 0) > 0
                            );
                        })
                        ->sortBy('amount', SORT_DESC)
                        ->first();

                    if (is_array($offer)) {
                        Event::dispatch(
                            new CPALeadOfferMailWasViewed(
                                $request->query('email'),
                                $offer['campid'],
                                Carbon::now()
                            )
                        );

                        return redirect()->to($offer['link'], Response::HTTP_MOVED_PERMANENTLY);
                    }
                }

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