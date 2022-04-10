<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

use App\Models\User;
use BADDIServices\SourceeApp\Models\Pack;
use BADDIServices\SourceeApp\Services\Service;
use Stripe\Checkout\Session;
use Twocheckout;

class TwoCheckoutService extends Service
{
    public function __construct()
    {
        Twocheckout::privateKey(config('2checkout.private_key'));
        Twocheckout::sellerId(config('2checkout.seller_id'));

        if (app()->environment() !== 'production') {
            Twocheckout::verifySSL(false);
        }

        Twocheckout::format('json');
    }

    public function getCheckoutSessionUrl(Pack $pack, User $user): string
    {
        $checkoutSession = Session::create([
            'mode'          => 'subscription',
            'success_url'   => route('subscription.billing.confirmation', ['pack' => $pack->getId()]),
            'cancel_url'    => route('subscription.billing.cancel', ['pack' => $pack->getId()]),
            'client_reference_id'               => $user->getId(),
            'customer_email'                    => $user->email,
            'line_items'                        => [
                [
                    'quantity'                  => 1,
                    'price_data'                => [
                        'unit_amount'           => $pack->price * 100,
                        'currency'              => $pack->currency ?? 'USD',
                        'recurring'             => [
                            'interval'          => $pack->interval,
                            'interval_count'    => 1
                        ],
                        'product_data'              => [
                            'name'                  => $pack->name,
                        ]
                    ]
                ]
            ]
        ]);

        return $checkoutSession->url;
    }
}