<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use BADDIServices\SourceeApp\Models\Pack;
use Illuminate\Foundation\Bus\DispatchesJobs;
use BADDIServices\SourceeApp\Models\Subscription;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Domains\FeatureService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /** @var FeatureService */
    protected $featureService;

    /** @var UserService */
    protected $userService;

    /** @var User */
    protected $user;
    
    /** @var Subscription */
    protected $subscription;

    /** @var Pack */
    protected $pack;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var FeatureService */
            $this->featureService = app(FeatureService::class);
            
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

    public function render(string $name, array $data = []): View|Factory
    {
        return view($name, array_merge($this->defaultData(), $data));
    }

    private function defaultData(): array
    {
        return [
            'featureService'    => $this->featureService,
            'user'              => $this->user,
            'subscription'      => $this->subscription,
            'pack'              => $this->pack,
        ];
    }
}