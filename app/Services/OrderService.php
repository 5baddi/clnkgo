<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Services;

use BADDIServices\SocialRocket\Models\Store;
use BADDIServices\SocialRocket\Models\Order;
use BADDIServices\SocialRocket\Repositories\OrderRepository;
use Illuminate\Support\Arr;

class OrderService extends Service
{
    /** @var OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function latest(): Order
    {
        return $this->orderRepository->latest();
    }

    public function save(Store $store, array $attributes): Order
    {
        Arr::set($attributes, Order::STORE_ID_COLUMN, $store->id);
        Arr::set($attributes, Order::ORDER_ID_COLUMN, $attributes[Order::ID_COLUMN]);
        Arr::set($attributes, Order::CUSTOMER_ID_COLUMN, $attributes['customer'][Order::ID_COLUMN]);

        $attributes = collect($attributes)
                        ->only([
                            Order::STORE_ID_COLUMN,
                            Order::CUSTOMER_ID_COLUMN,
                            Order::ORDER_ID_COLUMN,
                            Order::CHECKOUT_ID_COLUMN,
                            Order::NAME_COLUMN,
                            Order::TOTAL_PRICE_COLUMN,
                            Order::TOTAL_PRICE_USD_COLUMN,
                            Order::CURRENCY_COLUMN,
                            Order::DISCOUNT_CODES_COLUMN,
                            Order::TOTAL_DISCOUNTS_COLUMN,
                            Order::CONFIRMED_COLUMN,
                            Order::CANCELLED_AT_COLUMN,
                        ])
                        ->toArray();

        return $this->orderRepository->save(
            [
                Order::ORDER_ID_COLUMN    => $attributes[Order::ORDER_ID_COLUMN],
                Order::STORE_ID_COLUMN    => $attributes[Order::STORE_ID_COLUMN],
                Order::CUSTOMER_ID_COLUMN => $attributes[Order::CUSTOMER_ID_COLUMN]
            ],
            $attributes
        );
    }
}