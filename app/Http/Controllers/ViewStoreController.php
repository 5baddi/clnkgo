<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers;

use BADDIServices\SourceeApp\Models\Store;
use Illuminate\Routing\Controller as BaseController;
use BADDIServices\SourceeApp\Services\ShopifyService;

class ViewStoreController extends BaseController
{
    /** @var ShopifyService */
    protected $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    public function __invoke(Store $store)
    {
        return redirect(
            $this->shopifyService->getStoreURL($store->slug)
        );
    }
}