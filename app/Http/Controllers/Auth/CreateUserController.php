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
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Http\Requests\SignUpRequest;
use BADDIServices\SourceeApp\Models\Pack;
use BADDIServices\SourceeApp\Services\PackService;
use BADDIServices\SourceeApp\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class CreateUserController extends Controller
{
    /** @var UserService */
    private $userService;

    /** @var PackService */
    private $packService;

    /** @var SubscriptionService */
    private $subscriptionService;

    public function __construct(UserService $userService, PackService $packService, SubscriptionService $subscriptionService)
    {
        $this->userService = $userService;
        $this->packService = $packService;
        $this->subscriptionService = $subscriptionService;
    }

    public function __invoke(SignUpRequest $request)
    {
        try {
            $existsEmail = $this->userService->findByEmail($request->input(User::EMAIL_COLUMN));
            if ($existsEmail) {
                return redirect('/signup')->withInput()->with("error", "Email already registred with another account");
            }

            DB::beginTransaction();

            $user = $this->userService->create($request->input());
            $pack = $this->packService->findByName('The plan');

            abort_unless($user instanceof User && $pack instanceof Pack, Response::HTTP_UNPROCESSABLE_ENTITY, 'Unprocessable user entity');

            $this->subscriptionService->startTrial($user, $pack);

            DB::commit();

            Event::dispatch(new WelcomeMail($user));

            $authenticateUser = Auth::loginUsingId($user->getId());
            if (! $authenticateUser) {
                return redirect('/signin')->with('error', 'Something going wrong with the authentification');
            }

            return redirect('/dashboard')->with('success', 'Account created successfully');
        } catch (ValidationException $ex) {
            DB::rollBack();

            AppLogger::error($ex, 'client:create-account', $request->all());

            return redirect('/signup')->withInput()->withErrors($ex->errors());
        }  catch (Throwable $ex) {
            DB::rollBack();

            AppLogger::error($ex, 'client:create-account', $request->all());
            
            return redirect()->route('signup')->withInput()->with("error", "Internal server error");
        }
    }
}
