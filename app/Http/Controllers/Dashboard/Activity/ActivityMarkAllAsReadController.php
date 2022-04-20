<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Activity;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivityMarkAllAsReadController extends Controller
{
    public function __invoke()
    {
        /** @var User */
        $user = Auth::user();

        $user->notifications->markAsRead();

        // return redirect()->route('dashboard.activity');
        return redirect()->route('dashboard');
    }
}