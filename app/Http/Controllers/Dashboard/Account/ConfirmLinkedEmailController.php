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

class ConfirmLinkedEmailController extends DashboardController
{    
    public function __invoke(string $token)
    {
        try {
            $linkedEmail = $this->userService->findLinkedEmailByToken($token);
            abort_unless($linkedEmail instanceof UserLinkedEmail, Response::HTTP_NOT_FOUND);

            $this->userService->confirmLinkedEmail($linkedEmail);

            return redirect()->route('dashboard.account', ['tab' => 'emails'])
                ->with(
                    'alert', 
                    new Alert('Linked email successfully confirmed.', 'success')
                );
        } catch (Throwable $e){
            return redirect()->route('dashboard.account', ['tab' => 'emails'])
                ->with(
                    'alert', 
                    new Alert('An occurred error while confirming linked email!')
                );
        }
    }
}