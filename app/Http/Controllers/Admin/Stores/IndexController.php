<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Stores;

use App\Http\Requests\AnalyticsRequest;
use BADDIServices\SourceeApp\Services\SubscriptionService;
use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;

class IndexController extends ControllersAdminController
{    
    /** @var SubscriptionService */
    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();
        
        $this->subscriptionService = $subscriptionService;
    }

    public function __invoke(AnalyticsRequest $request)
    {
        return view('admin.stores.index', [
            'title'                 =>  'stores',
            'subscriptions'         =>  $this->subscriptionService->paginateWithRelations($request->query('page'))
        ]);
    }
}