<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Admin\Clients;

use App\Models\User;
use Illuminate\Http\Request;
use BADDIServices\ClnkGO\Http\Controllers\AdminController;
use BADDIServices\ClnkGO\Http\Filters\Admin\User\ClientQueryFilter;

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