<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers;

use App\Models\User;
use BADDIServices\SourceeApp\Models\Pack;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use BADDIServices\SourceeApp\Models\Subscription;
use BADDIServices\SourceeApp\Services\UserService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var User */
    protected $user;
    
    /** @var Subscription */
    protected $subscription;

    /** @var UserService */
    protected $userService;

    /** @var Pack */
    protected $pack;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var UserService */
            $this->userService = app(UserService::class);

            $this->user = Auth::id() !== null ? $this->userService->findById(Auth::id()) : null;
            $this->subscription = $this->user->subscription;

            if ($this->subscription instanceof Subscription) {
                $this->subscription->load('pack');
                $this->pack = $this->subscription->pack;
            }

            if ($request->has('notification')) {
                $this->user->unreadNotifications->where('id', $request->query('notification'))->markAsRead();
            }

            return $next($request);
        });
    }
}