<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\OAuth;

use Throwable;
use App\Http\Controllers\Controller;
use BADDIServices\SourceeApp\AppLogger;
use Illuminate\Validation\ValidationException;
use BADDIServices\SourceeApp\Services\StoreService;
use BADDIServices\SourceeApp\Services\ShopifyService;
use BADDIServices\SourceeApp\Http\Requests\ConnectStoreRequest;
use BADDIServices\SourceeApp\Exceptions\Shopify\InvalidStoreURLException;
use BADDIServices\SourceeApp\Exceptions\Store\StoreAlreadyLinkedException;
use Illuminate\Support\Facades\Session;

class OAuthController extends Controller
{
    /** @var ShopifyService */
    private $shopifyService;

    /** @var StoreService */
    private $storeService;

    public function __construct(ShopifyService $shopifyService, StoreService $storeService)
    {
        $this->shopifyService = $shopifyService;
        $this->storeService = $storeService;
    }
    
    public function __invoke(ConnectStoreRequest $request)
    {
        try {
            $storeName = $this->shopifyService->getStoreName($request->get('store'));

            if (is_null($storeName)) {
                throw new InvalidStoreURLException();
            }

            $storeIsLinked = $this->storeService->isLinked($storeName);
            if($storeIsLinked) {
                throw new StoreAlreadyLinkedException();
            }

            $store = $this->storeService->create([
                'slug'  =>  $storeName
            ]);

            $oauthURL = $this->shopifyService->getOAuthURL($store);

            Session::put('store', $store->id);

            return redirect($oauthURL);
        } catch (ValidationException $ex) {
            AppLogger::setStore($store ?? null)->error($ex, 'store:redirect-oauth', $request->all());
            
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (InvalidStoreURLException | StoreAlreadyLinkedException $ex) {
            AppLogger::setStore($store ?? null)->error($ex, 'store:redirect-oauth', $request->all());

            return redirect()->back()->withInput()->with("error", $ex->getMessage());
        } catch (Throwable $ex) {
            AppLogger::setStore($store ?? null)->error($ex, 'store:redirect-oauth', $request->all());

            return redirect()->back()->withInput()->with("error", "Internal server error");
        }
    }
}