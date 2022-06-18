<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Middleware;

use BADDIServices\ClnkGO\Domains\PayPalService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class IsPayPalWebhook
{
    public function __construct(
        private PayPalService $payPalService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Arr::has($request->header(), PayPalService::HEADERS)) {
            abort(Response::HTTP_BAD_GATEWAY);
        }
        
        if (! Arr::has($request->input(), ['event_type', 'id'])) {
            abort(Response::HTTP_BAD_REQUEST);
        }

        if (! $this->payPalService->verifySignature(
            Arr::only($request->header(), PayPalService::HEADERS),
            $request->input('event_type'),
            $request->input('id')
        )) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}