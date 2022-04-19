<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use BADDIServices\SourceeApp\AppLogger;
use Illuminate\Validation\ValidationException;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Http\Requests\SignInRequest;

class AuthenticateController extends Controller
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(SignInRequest $request)
    {
        try {
            $user = $this->userService->findByEmail($request->input(User::EMAIL_COLUMN));
            if (! $user) {
                return redirect()
                    ->route('signin')
                    ->withInput($request->only([User::EMAIL_COLUMN]))
                    ->with("error", "No account registred with those credentials");
            }

            if (! $this->userService->verifyPassword($user, $request->input(User::PASSWORD_COLUMN))) {
                return redirect()
                    ->route('signin')
                    ->withInput($request->only([User::EMAIL_COLUMN]))
                    ->with('error', 'Incorrect credentials, try again...');
            }

            $authenticateUser = Auth::attempt(['email' => $user->email, 'password' => $request->input(User::PASSWORD_COLUMN)]);
            if (! $authenticateUser) {
                return redirect()
                    ->route('signin')
                    ->withInput($request->only([User::EMAIL_COLUMN]))
                    ->with('error', 'Something going wrong with the authentification');
            }

            $this->userService->update($user, [
                User::LAST_LOGIN_COLUMN    =>  Carbon::now()
            ]);
            
            return redirect()
                ->route('dashboard')
                ->with('success', 'Welcome back ' . strtoupper($user->first_name));
        } catch (ValidationException $e) {
            return redirect()
                ->route('signin')
                ->withInput($request->only([User::EMAIL_COLUMN]))
                ->withErrors($e->errors());
        }  catch (Throwable $e) {
            AppLogger::error($e, 'auth:signin', ['playload' => $request->all()]);

            return redirect()
                ->route('signin')
                ->withInput($request->only([User::EMAIL_COLUMN]))
                ->with("error", "Internal server error");
        }
    }
}