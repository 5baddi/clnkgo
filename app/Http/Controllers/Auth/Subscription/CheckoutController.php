<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription;

use Illuminate\Http\Response;
use BADDIServices\SourceeApp\Models\Pack;
use BADDIServices\SourceeApp\Services\PackService;
use BADDIServices\SourceeApp\Domains\StripeService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class CheckoutController extends DashboardController
{
    /** @var PackService */
    private $packService;

    private StripeService $stripeService;

    public function __construct(PackService $packService, StripeService $stripeService)
    {
        parent::__construct();

        $this->packService = $packService;
        $this->stripeService = $stripeService;
    }

    public function __invoke(string $id)
    {
        $pack = $this->packService->findById($id);
        abort_unless($pack instanceof Pack, Response::HTTP_NOT_FOUND);

        $checkoutUrl = $this->stripeService->getCheckoutSessionUrl($pack, $this->user);

        return redirect()->to($checkoutUrl, Response::HTTP_SEE_OTHER);

        // return view(
        //     'dashboard.plan.checkout', 
        //     [
        //         'title' => 'Checkout',
        //         'pack'  => $pack
        //     ]
        // );
    }
}
