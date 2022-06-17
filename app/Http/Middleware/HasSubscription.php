<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Middleware;

use Closure;
use App\Models\User;
use BADDIServices\ClnkGO\Entities\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use BADDIServices\ClnkGO\Models\Subscription;

class HasSubscription
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User */
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (
            strpos($request->path(), "dashboard") === 0 
            && strpos($request->path(), "logout") === false
            && ! $request->routeIs('dashboard.plan.*')
        ) {
            $user->load('subscription');

            /** @var Subscription */
            $subscription = $user->subscription;

            if(! $subscription instanceof Subscription || $subscription->trashed() || ! $subscription->isActive()) {
                return redirect()
                    ->route('dashboard.plan.upgrade')
                    ->with(
                        'alert',
                        new Alert('You should upgrade your subscription to keep using the platform!')
                    );
            }
        }

        return $next($request);
    }
}