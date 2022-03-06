<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Users;

use App\Models\User;
use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;

class BanController extends ControllersAdminController
{
    public function __invoke(User $user)
    {
        $this->userService->ban($user);

        return redirect()
                ->back()
                ->with(
                    'alert',
                    new Alert(sprintf('User %sbanned successfully', $user->isBanned() ? 'un' : ''), 'success')
                );
    }
}