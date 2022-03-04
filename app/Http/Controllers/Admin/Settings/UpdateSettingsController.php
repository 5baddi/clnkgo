<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Settings;

use Throwable;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Entities\Alert;
use Illuminate\Validation\ValidationException;
use BADDIServices\SourceeApp\Services\AppService;
use BADDIServices\SourceeApp\Http\Requests\Admin\Settings\UpdateSettingsRequest;
use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;

class UpdateSettingsController extends ControllersAdminController
{
    /** @var AppService */
    private $appService;

    public function __construct(AppService $appService)
    {
        $this->appService = $appService;
    }

    public function __invoke(UpdateSettingsRequest $request)
    {
        try {
            // $this->appService->update($request->input());

            return redirect()
                ->back()
                ->with(
                    'alert', 
                    new Alert('Settings has been saved successfully', 'success')
                );
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (Throwable $e) {
            AppLogger::error($e, 'admin:update-settings');

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'alert', 
                    new Alert('Error during save settings')
                );
        }
    }
}