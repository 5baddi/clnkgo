<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription;

use Throwable;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Models\Store;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Services\UserService;

class CancelController extends Controller
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke()
    {
        try {
            /** @var User */
            $user = Auth::user();
            
            $this->userService->delete($user);

            return redirect()->route('landing');
        } catch(Throwable $e) {
            AppLogger::setStore($store ?? null)->error($e, 'store:delete-account');

            return redirect()->route('subscription.select.pack')->with('error', 'Internal server error');
        }
    }
}