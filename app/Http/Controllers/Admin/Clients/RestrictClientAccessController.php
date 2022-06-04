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

class RestrictClientAccessController extends AdminController
{
    public function __invoke(string $id, Request $request)
    {
        try {
            $user = $this->userService->findById($id);
            abort_unless($user instanceof User, Response::HTTP_NOT_FOUND);

            DB::beginTransaction();

            $this->userService->restrict($user);
    
            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'alert',
                    new Alert(sprintf('Client account %sbanned successfully', $user->isBanned() ? 'un' : ''), 'success')
                );
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error($e, 'admin:restrict-client', ['user' => $user ?? $id, 'playload' => $request->all()]);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'alert', 
                    new Alert('Error during restricting client account')
                );
        }
    }
}