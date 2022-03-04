<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\OAuth;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Models\OAuth;
use BADDIServices\SourceeApp\Models\Store;
use BADDIServices\SourceeApp\Entities\Alert;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Events\WelcomeMail;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Services\StoreService;
use BADDIServices\SourceeApp\Services\ShopifyService;
use BADDIServices\SourceeApp\Http\Requests\OAuthCallbackRequest;
use Illuminate\Support\Facades\DB;

class OAuthCallbackController extends Controller
{
    /** @var ShopifyService */
    private $shopifyService;

    /** @var StoreService */
    private $storeService;

    /** @var UserService */
    private $userService;

    public function __construct(
        ShopifyService $shopifyService, 
        StoreService $storeService,
        UserService $userService
    )
    {
        $this->shopifyService = $shopifyService;
        $this->storeService = $storeService;
        $this->userService = $userService;
    }
    
    public function __invoke(OAuthCallbackRequest $request)
    {
        try {
            $storeName = $this->shopifyService->getStoreName($request->query('shop'));
            $store = $this->storeService->findBySlug($storeName);
            if (!$store instanceof Store) {
                abort(Response::HTTP_NOT_FOUND, 'Store not found');
            }

            $accessToken = $this->shopifyService->getStoreAccessToken($request->query());
            $attributes = $request->merge($accessToken)->only([
                OAuth::STORE_ID_COLUMN,
                OAuth::CODE_COLUMN,
                OAuth::ACCESS_TOKEN_COLUMN,
                OAuth::SCOPE_COLUMN,
                OAuth::TIMESTAMP_COLUMN,
            ]);

            DB::beginTransaction();

            $oauth = $this->storeService->updateStoreOAuth($store, $attributes);
            abort_unless($oauth instanceof OAuth, Response::HTTP_BAD_REQUEST, 'Something going wrong during authentification');

            $store = $this->storeService->updateConfigurations($store);
            $this->storeService->enableStore($store);

            $user = $this->userService->findByEmail($store->email);
            if (!$user instanceof User) {
                $firstName = ucwords($store->name);
                $lastName = 'Admin';

                $shopOwner = explode(' ', $store->shop_owner);
                if (sizeof($shopOwner) === 2) {
                    $firstName = ucfirst($shopOwner[0]);
                    $lastName = ucfirst($shopOwner[1]);
                }

                $user = $this->userService->create(
                    $store,
                    [
                        User::FIRST_NAME_COLUMN    => $firstName,
                        User::LAST_NAME_COLUMN     => $lastName,
                        User::EMAIL_COLUMN         => $store->email,
                        User::PHONE_COLUMN         => $store->phone
                    ]
                );
            }

            Event::dispatch(new WelcomeMail($store, $user));

            $authenticateUser = Auth::loginUsingId($user->id);
            if (!$authenticateUser) {
                return redirect()
                    ->route('signin')
                    ->with('error', 'Something going wrong with the authentification');
            }

            $this->userService->update($user, [
                User::LAST_LOGIN_COLUMN    =>  Carbon::now()
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard')
                ->with(
                    'alert',
                    new Alert('Your store has been linked successfully', 'success')
                );
        } catch (ValidationException $ex) {
            AppLogger::setStore($store ?? null)->error($ex, 'store:oauth-callback', $request->all());
            
            return redirect()
                ->route('connect')
                ->withInput()
                ->withErrors($ex->errors());
        } catch (Throwable $ex) {
            DB::rollBack();

            AppLogger::setStore($store ?? null)->error($ex, 'store:oauth-callback', $request->all());
            
            return redirect()
                ->route('connect')
                ->with('error', 'Internal server error');
        }
    }
}