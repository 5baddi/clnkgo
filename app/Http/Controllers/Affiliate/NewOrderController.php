<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Affiliate;

use Throwable;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Models\Order;
use BADDIServices\SourceeApp\Models\Store;
use BADDIServices\SourceeApp\Models\Setting;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Models\Commission;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Entities\StoreSetting;
use BADDIServices\SourceeApp\Services\OrderService;
use BADDIServices\SourceeApp\Services\StoreService;
use BADDIServices\SourceeApp\Services\CouponService;
use BADDIServices\SourceeApp\Services\ProductService;
use BADDIServices\SourceeApp\Services\ShopifyService;
use BADDIServices\SourceeApp\Services\CommissionService;
use BADDIServices\SourceeApp\Exceptions\Shopify\OrderNotFound;
use BADDIServices\SourceeApp\Exceptions\Shopify\ProductNotFound;
use BADDIServices\SourceeApp\Exceptions\Shopify\CustomerNotFound;
use BADDIServices\SourceeApp\Http\Controllers\AffiliateController;
use BADDIServices\SourceeApp\Http\Requests\Affiliate\NewOrderRequest;
use BADDIServices\SourceeApp\Exceptions\Shopify\CreatePriceRuleFailed;
use BADDIServices\SourceeApp\Jobs\NotifyAboutNewOrder;
use Illuminate\Support\Collection;

class NewOrderController extends AffiliateController
{
    /** @var UserService */
    private $userService;

    /** @var ProductService */
    private $productService;
    
    /** @var OrderService */
    private $orderService;
    
    /** @var CouponService */
    private $couponService;

    /** @var CommissionService */
    private $commissionService;

    public function __construct(StoreService $storeService, ShopifyService $shopifyService, UserService $userService, OrderService $orderService, ProductService $productService, CouponService $couponService, CommissionService $commissionService)
    {
        parent::__construct($storeService, $shopifyService);

        $this->userService = $userService;
        $this->orderService = $orderService;
        $this->productService = $productService;
        $this->couponService = $couponService;
        $this->commissionService = $commissionService;
    }

    public function __invoke(NewOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $store = $this->storeService->findBySlug($request->get(Store::SLUG_COLUMN));
            if (! $store instanceof Store) {
                return response()->json([], Response::HTTP_NO_CONTENT);
            }

            $setting = $store->getSetting();
            if (! $setting->isThankYouPageEnabled()) {
                return response()->json([], Response::HTTP_NO_CONTENT);
            }

            NotifyAboutNewOrder::dispatch($request->get(Store::SLUG_COLUMN), $request->input(Order::ORDER_ID_COLUMN));

            $shopifyOrder = Collection::make($this->shopifyService->getOrder($store, $request->input(Order::ORDER_ID_COLUMN)));

            $lineItems = Collection::make($shopifyOrder->get('line_items', []));
            $productData = Collection::make($lineItems->first());

            if ($productData->has('product_id')) {
                $shopifyProduct = $this->shopifyService->getProduct($store, $productData->get('product_id'));
                $productSlug = Arr::get($shopifyProduct, 'handle');
            }

            $affiliate = $this->userService->exists($shopifyOrder->get('customer.id'));
            if (! $affiliate instanceof User) {
                $affiliate = $this->userService->create($store, $$shopifyOrder->get('customer'), true);
            }

            DB::commit();

            return response()->json([
                User::COUPON_COLUMN     => $affiliate->coupon,
                'discount'              => $this->couponService->getDiscount($setting->discount_amount, $setting->discount_type, $setting->currency),
                'color'                 => $setting->color,
                'url'                   => $this->shopifyService->getProductWithDiscountURL($store, $productSlug, $affiliate->coupon)
            ]);
        } catch (CustomerNotFound | OrderNotFound | ProductNotFound | CreatePriceRuleFailed $ex) {
            DB::rollBack();

            AppLogger::setStore($store)->error($ex, 'affiliate:new-order', ['playload' => $request->all()]);

            return response()->json($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $ex) {
            DB::rollBack();

            AppLogger::setStore($store)->error($ex, 'affiliate:new-order', ['playload' => $request->all()]);
            
            return response()->json('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}