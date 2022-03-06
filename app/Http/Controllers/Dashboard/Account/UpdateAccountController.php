<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Account;

use Throwable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BADDIServices\SourceeApp\Http\Requests\UpdateAccountRequest;
use Illuminate\Validation\ValidationException;

class UpdateAccountController extends DashboardController
{    
    public function __invoke(UpdateAccountRequest $request)
    {
        try {
            if (!is_null($request->input('current_password')) && !$this->userService->verifyPassword($this->user, $request->input('current_password'))) {
                return redirect()->route('dashboard.account')
                    ->with(
                        'alert', 
                        new Alert('Current passwrod not match your credential')
                    )
                    ->withInput();
            }
            
            if ($request->input(User::EMAIL_COLUMN) !== $this->user->email && $this->userService->findByEmail($request->input(User::EMAIL_COLUMN)) instanceof User) {
                return redirect()->route('dashboard.account')
                    ->with(
                        'alert', 
                        new Alert('E-mail already taken by another account')
                    )
                    ->withInput();
            }

            $this->user = $this->userService->update($this->user, $request->input());
            Auth::setUser($this->user);

            return redirect()->route('dashboard.account', ['tab' => $request->query('tab', 'settings')])
                ->with(
                    'alert', 
                    new Alert('Account settings changed successfully', 'success')
                );
        } catch (ValidationException $ex){
            return redirect()->route('dashboard.account', ['tab' => $request->query('tab', 'settings')])
                ->withErrors($ex->errors)
                ->withInput();
        } catch (NotFoundHttpException $ex){
            return redirect()->route('dashboard.account', ['tab' => $request->query('tab', 'settings')])
                ->with(
                    'alert', 
                    new Alert($ex->getMessage())
                )
                ->withInput();
        } catch (Throwable $ex){
            return redirect()->route('dashboard.account', ['tab' => $request->query('tab', 'settings')])
                ->with(
                    'alert', 
                    new Alert('Error saving account settings')
                )
                ->withInput();
        }
    }
}