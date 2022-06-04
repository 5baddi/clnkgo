<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Clients;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Http\Controllers\AdminController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ResetClientController extends AdminController
{
    public function __invoke(string $id, Request $request)
    {
        try {
            $user = $this->userService->findById($id);
            abort_unless($user instanceof User, Response::HTTP_NOT_FOUND);

            DB::beginTransaction();

            $this->userService->update(
                $user, 
                [
                    User::PASSWORD_COLUMN => $request->input(User::PASSWORD_COLUMN)
                ]);
    
            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'alert',
                    new Alert('Client account password has been reseted successfully', 'success')
                );
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error($e, 'admin:reset-user-password', ['user' => $user ?? $id, 'playload' => $request->all()]);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'alert', 
                    new Alert('Error during reseting client account password')
                );
        }
    }
}