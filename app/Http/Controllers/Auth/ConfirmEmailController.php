<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Entities\Alert;
use BADDIServices\ClnkGO\Services\UserService;
use Illuminate\Support\Facades\DB;

class ConfirmEmailController extends Controller
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(string $token)
    {
        try {
            $user = $this->userService->findByToken($token);
            abort_unless($user instanceof User, Response::HTTP_NOT_FOUND);

            DB::beginTransaction();
            $this->userService->confirmEmail($user);
            DB::commit();

            return redirect()
                ->route('dashboard')
                ->with(
                    'alert', 
                    new Alert('Your email successfully confirmed.', 'success')
                );
        } catch (Throwable $e){
            DB::rollBack();

            AppLogger::error($e, 'auth:confirm-email', ['token' => $token]);

            return redirect()
                ->route('auth.signin')
                ->with('error', 'An occurred error while confirming your email!');
        }
    }
}