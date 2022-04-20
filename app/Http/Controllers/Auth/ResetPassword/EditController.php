<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth\ResetPassword;

use App\Models\User;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Services\UserService;

class EditController extends Controller
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(string $token)
    {
        $user = $this->userService->verifyResetPasswordToken($token);
        abort_if(!$user instanceof User, Response::HTTP_NOT_FOUND);

        return view('auth.password', [
            'token'     =>  $token
        ]);
    }
}