<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use BADDIServices\SourceeApp\Models\Subscription;

class HasSubscription
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User */
        $user = Auth::user();

        // if (
        //     strpos($request->path(), "dashboard") === 0 
        //     && strpos($request->path(), "logout") === false
        // ) {
        //     $user->load('subscription');

        //     /** @var Subscription */
        //     $subscription = $user->subscription;

        //     if(!$subscription instanceof Subscription || $subscription->trashed()) {
        //         return redirect()->route('subscription.select.pack');
        //     }
        // }

        return $next($request);
    }
}