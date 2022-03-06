<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use BADDIServices\SourceeApp\Models\Pack;
use BADDIServices\SourceeApp\Services\PackService;

class SubscriptionController extends Controller
{
    /** @var PackService */
    private $packService;

    public function __construct(PackService $packService)
    {
        $this->packService = $packService;
    }

    public function __invoke()
    {
        $currentPack = $this->packService->loadCurrentPack(Auth::user());
        if ($currentPack instanceof Pack) {
            return redirect()->route('dashboard.plan.upgrade');
        }

        return view('auth.subscription.index', [
            'packs'         =>  $this->packService->all(),
            'currentPack'   =>  $currentPack
        ]);
    }
}