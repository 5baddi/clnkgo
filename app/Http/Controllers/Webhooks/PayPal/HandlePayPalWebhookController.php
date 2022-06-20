<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Webhooks\PayPal;

use Throwable;
use Illuminate\Http\Response;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Domains\PayPalService;
use App\Http\Requests\Webhooks\PayPalWebhookRequest;
use BADDIServices\ClnkGO\Http\Controllers\WebhookController;

class HandlePayPalWebhookController extends WebhookController
{
    public function __construct(
        private PayPalService $payPalService
    ) {
        parent::__construct();
    }

    public function __invoke(PayPalWebhookRequest $request)
    {
        dd($request);
        try {
            if ($request->input('event_type') === PayPalService::SUBSCRIPTION_CANCELLED_EVENT) {
                $this->payPalService->handleCancelledSubscription($request->input('resource'));
            }
            
            return response()
                ->json(['success' => true]);
        } catch (Throwable $e) {
            dd($e);
            AppLogger::error(
                $e,
                'webhooks:paypal', 
                ['payload' => $request->all(), 'headers' => $request->header()]
            );

            return response()
                ->json(
                    [
                        'success'   => false,
                        'error'     => 'Internal server error!',
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
        }
    }
}