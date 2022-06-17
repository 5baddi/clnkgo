<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Account;

use Throwable;
use BADDIServices\ClnkGO\Entities\Alert;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Models\UserLinkedEmail;
use Illuminate\Http\Response;

class RemoveLinkedEmailController extends DashboardController
{    
    public function __invoke(string $id)
    {
        try {
            $linkedEmail = $this->userService->findLinkedEmailById($id);
            abort_unless($linkedEmail instanceof UserLinkedEmail, Response::HTTP_NOT_FOUND);

            $this->userService->removeLinkedEmail($linkedEmail);

            return redirect()->route('dashboard.account', ['tab' => 'emails'])
                ->with(
                    'alert', 
                    new Alert('Linked email successfully removed.', 'success')
                );
        } catch (Throwable $e){
            return redirect()->route('dashboard.account', ['tab' => 'emails'])
                ->with(
                    'alert', 
                    new Alert('An occurred error while removing linked email!')
                );
        }
    }
}