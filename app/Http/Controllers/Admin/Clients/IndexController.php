<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Clients;

use App\Models\User;
use BADDIServices\SourceeApp\Http\Controllers\AdminController;
use BADDIServices\SourceeApp\Http\Filters\User\ClientQueryFilter;
use Illuminate\Http\Request;

class IndexController extends AdminController
{
    public function __invoke(Request $request, ClientQueryFilter $queryFilter)
    {
        $request->merge(['role' => [User::DEFAULT_ROLE, User::JOURNALIST_ROLE]]);

        $clients = $this->userService->paginate($queryFilter);

        return $this->render(
            'admin.clients.index',
            [
                'title'     => 'Manage clients',
                'clients'   => $clients
            ]
        );
    }
}