<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use BADDIServices\SocialRocket\Models\Order;
use BADDIServices\SocialRocket\Models\OrderProduct;
use BADDIServices\SocialRocket\Models\Product;
use BADDIServices\SocialRocket\Models\Setting;
use BADDIServices\SocialRocket\Models\Store;
use BADDIServices\SocialRocket\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderService extends Service
{
    /** @var OrderRepository */
    private $orderRepository;
    
    /** @var CommissionService */
    private $commissionService;

    public function __construct(OrderRepository $orderRepository, CommissionService $commissionService)
    {
        $this->orderRepository = $orderRepository;
        $this->commissionService = $commissionService;
    }

    public function latest(): ?Order
    {
        return $this->orderRepository->latest();
    }
    
    public function exists(Store $store, string $orderId): ?Order
    {
        return $this->orderRepository->exists($store->id, $orderId);
    }

    public function save(Store $store, array $attributes): Order
    {
        $productsIds = collect($attributes['line_items'], [])->pluck('product_id');

        Arr::set($attributes, Order::STORE_ID_COLUMN, $store->id);
        Arr::set($attributes, Order::ORDER_ID_COLUMN, $attributes[Order::ID_COLUMN]);
        Arr::set($attributes, Order::CUSTOMER_ID_COLUMN, $attributes['customer'][Order::ID_COLUMN]);
        Arr::set($attributes, Order::PRODUCTS_IDS_COLUMN, $productsIds->toArray());

        $filteredAttributes = collect($attributes)
                        ->only([
                            Order::STORE_ID_COLUMN,
                            Order::CUSTOMER_ID_COLUMN,
                            Order::ORDER_ID_COLUMN,
                            Order::CHECKOUT_ID_COLUMN,
                            Order::NAME_COLUMN,
                            Order::TOTAL_PRICE_COLUMN,
                            Order::TOTAL_PRICE_USD_COLUMN,
                            Order::CURRENCY_COLUMN,
                            Order::PRODUCTS_IDS_COLUMN,
                            Order::DISCOUNT_CODES_COLUMN,
                            Order::TOTAL_DISCOUNTS_COLUMN,
                            Order::CONFIRMED_COLUMN,
                            Order::CANCELLED_AT_COLUMN,
                            Order::CREATED_AT,
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
    
    public function attachProduct(Store $store, Order $order, array $product): OrderProduct
    {
        $product = collect($product);
        $price = collect($product->get('price_set'));
        $money = collect($price->get('shop_money'));
        $currency = $money->get('currency_code', Setting::CURRENCY_COLUMN);

        return $this->orderRepository->attachProduct(
            [
                OrderProduct::STORE_ID_COLUMN           => $store->id,
                OrderProduct::ORDER_ID_COLUMN           => $order->id,
                OrderProduct::PRODUCT_ID_COLUMN         => $product->get(Product::PRODUCT_ID_COLUMN),
            ],
            [
                OrderProduct::STORE_ID_COLUMN           => $store->id,
                OrderProduct::ORDER_ID_COLUMN           => $order->id,
                OrderProduct::PRODUCT_ID_COLUMN         => $product->get(Product::PRODUCT_ID_COLUMN),
                OrderProduct::PRICE_COLUMN              => $product->get(OrderProduct::PRICE_COLUMN),
                OrderProduct::CURRENCY_COLUMN           => $currency
            ]
        );
    }

    public function whereInPeriod(Store $store, CarbonPeriod $period): Collection
    {
        return $this->orderRepository->whereInPeriod(
            $store->id,
            $period->getStartDate(),
            $period->getEndDate()
        );
    }
    
    public function getOrdersProductsIds(Store $store, CarbonPeriod $period): Collection
    {
        return dd($this->orderRepository->getOrdersProductsIds(
            $store->id,
            $period->getStartDate(),
            $period->getEndDate()
        ));
    }
    
    public function getOrdersEarnings(Store $store, CarbonPeriod $period): float
    {
        return $this->orderRepository->getOrdersEarnings(
            $store->id, 
            $period->copy()->getStartDate(),
            $period->copy()->getEndDate()
        );
    }
    
    public function getNewOrdersCount(Store $store, CarbonPeriod $period): int
    {
        return $this->orderRepository->getNewOrdersCount(
            $store->id, 
            $period->copy()->getStartDate(),
            $period->copy()->getEndDate()
        );
    }
}