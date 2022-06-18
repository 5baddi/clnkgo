<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Auth\Subscription;

use Illuminate\Http\Response;
use BADDIServices\ClnkGO\Models\Pack;
use BADDIServices\ClnkGO\Services\PackService;
use BADDIServices\ClnkGO\Domains\StripeService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

class CheckoutController extends DashboardController
{
    public function __construct(
        private PackService $packService, 
        private StripeService $stripeService)
    {
        parent::__construct();
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
