<?php

namespace BADDIServices\SourceeApp\Jobs;

use App\Models\User;
use BADDIServices\SourceeApp\Entities\StoreSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\SourceeApp\Models\Store;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Services\StoreService;
use BADDIServices\SourceeApp\Services\ShopifyService;
use BADDIServices\SourceeApp\Exceptions\Shopify\OrderNotFound;
use BADDIServices\SourceeApp\Exceptions\Shopify\ProductNotFound;
use BADDIServices\SourceeApp\Models\Commission;
use BADDIServices\SourceeApp\Models\Setting;
use BADDIServices\SourceeApp\Services\CommissionService;
use BADDIServices\SourceeApp\Services\OrderService;
use BADDIServices\SourceeApp\Services\ProductService;

class NotifyAboutNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $storeSlug;

    private string $orderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $storeSlug, string $orderId)
    {
        $this->storeSlug = $storeSlug;
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var StoreService */
        $storeService = app(StoreService::class);
        
        /** @var ShopifyService */
        $shopifyService = app(ShopifyService::class);
        
        /** @var UserService */
        $userService = app(UserService::class);

        $store = $storeService->findBySlug($this->storeSlug);
        if (! $store instanceof Store) {
            return;
        }

        try {
            $shopifyOrder = Collection::make($shopifyService->getOrder($store, $this->orderId));

            $lineItems = Collection::make($shopifyOrder->get('line_items', []));
            $productData = Collection::make($lineItems->first());

            $customer = Collection::make($shopifyOrder->get('customer'), []);
            $affiliater = $userService->exists($customer->get('id'));

            if (! $affiliater instanceof User) {
                $affiliate = $userService->create($store, $customer->toArray(), true);
                $setting = $store->getSetting();

                $shopifyService->createPriceRule(
                    $store, 
                    [
                        'title'         => $affiliate->coupon,
                        'value_type'    => $setting->discount_type === Setting::FIXED_TYPE ? 'fixed_amount' : $setting->discount_type,
                        'value'         => -$setting->discount_amount
                    ]
                );
            }

            $coupons = $userService->coupons($store);
            $discounts = Collection::make($shopifyOrder->get('discount_codes', []));

            $existsByCoupons = $discounts
                ->whereIn('code', $coupons)
                ->first();

            if (! is_null($existsByCoupons)) {  
                /** @var OrderService */
                $orderService = app(OrderService::class);
                
                /** @var CommissionService */
                $commissionService = app(CommissionService::class);

                /** @var ProductService */
                $productService = app(ProductService::class);

                $order = $orderService->save($store, $shopifyOrder->toArray());

                $commission = $commissionService->exists($store, $affiliate, $order);
                if (!$commission instanceof Commission) {
                    $commission = $commissionService->calculate($store, $affiliate, $order);
                }

                if ($productData->has('product_id')) {
                    $shopifyProduct = $shopifyService->getProduct($store, $productData->get('product_id'));
                    $product = $productService->save($store, $shopifyProduct);
                    $orderService->attachProduct($store, $order, $product, $lineItems);
                }
            }
        } catch (OrderNotFound|ProductNotFound $e) {}
    }
}
