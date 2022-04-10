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
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class CheckoutController extends DashboardController
{
    /** @var PackService */
    private $packService;

    public function __construct(PackService $packService)
    {
        parent::__construct();

        $this->packService = $packService;
    }

    public function __invoke(string $id)
    {
        $pack = $this->packService->findById($id);
        abort_unless($pack instanceof Pack, Response::HTTP_NOT_FOUND);

        return view(
            'dashboard.plan.checkout', 
            [
                'title' => 'Checkout',
                'pack'  => $pack
            ]
        );
    }
}
