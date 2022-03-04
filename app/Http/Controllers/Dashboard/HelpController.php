<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    public function __invoke()
    {
        if (!config('baddi.help_url')) {
            return redirect()->route('landing');
        }
        
        return redirect(config('baddi.help_url'));
    }
}