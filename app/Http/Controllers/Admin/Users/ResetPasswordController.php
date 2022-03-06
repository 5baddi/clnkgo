<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Users;

use Throwable;
use App\Models\User;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Entities\Alert;
use Illuminate\Validation\ValidationException;
use BADDIServices\SourceeApp\Http\Requests\Admin\Users\UpdatePasswordRequest;
use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;

class ResetPasswordController extends ControllersAdminController
{
    public function __invoke(User $user, UpdatePasswordRequest $request)
    {
        try {
            $this->userService->update(
                $user, 
                [
                    User::PASSWORD_COLUMN => $request->input(User::PASSWORD_COLUMN)
                ]);
    
            return redirect()
                ->back()
                ->with(
                    'alert',
                    new Alert('Account password has been reseted successfully', 'success')
                );
        } catch (ValidationException $e) {
            $errors = collect($e->errors());

            return redirect()
                ->back()
                ->with(
                    'alert',
                    new Alert($errors->first())
                );
        } catch (Throwable $e) {
            AppLogger::error($e, 'admin:reset-user-password', ['user' => $user, 'playload' => $request->all()]);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'alert', 
                    new Alert('Error during reseting account password')
                );
        }
    }
}