<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Middleware;

use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Domains\PayPalService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Throwable;

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
        try {
            $headers = [];

            array_map(
                function ($header) use ($request, &$headers) {
                    if (! $request->headers->has($header)) {
                        return;
                    }
    
                    $headers[$header] = $request->header($header);
                },
                PayPalService::HEADERS
            );

            if (! Arr::has($headers, PayPalService::HEADERS)) {
                return response()
                    ->json(null, Response::HTTP_BAD_GATEWAY);
            }
            
            if (! Arr::has($request->input(), ['event_type', 'id'])) {
                return response()
                    ->json(null, Response::HTTP_BAD_REQUEST);
            }
    
            if (! $this->payPalService->verifySignature(
                Arr::only($headers, PayPalService::HEADERS),
                $request->input('event_type'),
                $request->input('id')
            )) {
                return response()
                    ->json(null, Response::HTTP_UNAUTHORIZED);
            }
    
            return $next($request);
        } catch (Throwable $e) {
            AppLogger::error(
                $e,
                'middleware:is-paypal-webhook',
                ['payload' => $request->all(), 'headers' => $headers]
            );

            return response()
                ->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}