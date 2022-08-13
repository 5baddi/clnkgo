<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\CPALead;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CPALeadUnsubscribeController extends Controller
{
    public function __invoke(Request $request)
    {
        dd($request->query('email'));
        return redirect()->route('home');
    }
}