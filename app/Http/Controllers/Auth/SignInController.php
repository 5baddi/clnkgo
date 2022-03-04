<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class SignInController extends Controller
{
    public function __invoke()
    {
        return view('auth.signin');
    }
}