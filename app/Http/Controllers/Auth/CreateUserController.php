<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use App\Http\Controllers\Controller;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Events\WelcomeMail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\ClnkGO\Services\UserService;
use BADDIServices\ClnkGO\Http\Requests\SignUpRequest;
use BADDIServices\ClnkGO\Models\Pack;
use BADDIServices\ClnkGO\Services\PackService;
use BADDIServices\ClnkGO\Services\SubscriptionService;
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

            Event::dispatch(new WelcomeMail($user->getId(), $user->getConfirmationToken()));

            return redirect()
                ->route('signin')
                ->with('success', 'We sent an email link to get started with our platform 🥳');
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
