<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Domains\FeatureService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var User */
    protected $user;

    /** @var FeatureService */
    protected $featureService;

    /** @var UserService */
    protected $userService;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var FeatureService */
            $this->featureService = app(FeatureService::class);

            /** @var UserService */
            $this->userService = app(UserService::class);

            $this->user = Auth::id() !== null ? $this->userService->findById(Auth::id()) : null;

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
            'user'              => $this->user
        ];
    }
}