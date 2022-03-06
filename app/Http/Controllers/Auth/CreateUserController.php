<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use App\Http\Controllers\Controller;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Events\WelcomeMail;
use BADDIServices\SourceeApp\Models\Store;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class CreateUserController extends Controller
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(SignUpRequest $request)
    {
        try {
            $existsEmail = $this->userService->findByEmail($request->input(User::EMAIL_COLUMN));
            if ($existsEmail) {
                return redirect('/signup')->withInput()->with("error", "Email already registred with another account");
            }

            $user = $this->userService->create($request->input());
            abort_unless($user instanceof User, Response::HTTP_UNPROCESSABLE_ENTITY, 'Unprocessable user entity');

            Event::dispatch(new WelcomeMail($user));

            $authenticateUser = Auth::loginUsingId($user->getId());
            if (! $authenticateUser) {
                return redirect('/signin')->with('error', 'Something going wrong with the authentification');
            }

            return redirect('/dashboard')->with('success', 'Account created successfully');
        } catch (ValidationException $ex) {
            AppLogger::error($ex, 'client:create-account', $request->all());

            return redirect('/signup')->withInput()->withErrors($ex->errors());
        }  catch (Throwable $ex) {
            AppLogger::error($ex, 'client:create-account', $request->all());
            
            return redirect()->route('signup')->withInput()->with("error", "Internal server error");
        }
    }
}
