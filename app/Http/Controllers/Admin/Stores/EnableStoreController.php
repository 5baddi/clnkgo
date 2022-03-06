<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Stores;

use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Services\StoreService;
use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;
use BADDIServices\SourceeApp\Models\Store;

class EnableStoreController extends ControllersAdminController
{
    /** @var StoreService */
    private $storeService;

    public function __construct(StoreService $storeService)
    {
        parent::__construct();
        
        $this->storeService = $storeService;
    }

    public function __invoke(Store $store)
    {
        $this->storeService->enableStore($store);

        return redirect()
                ->back()
                ->with(
                    'alert',
                    new Alert('Store enabled successfully', 'success')
                );
    }
}