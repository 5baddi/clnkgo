<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Keywords;

use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\KeywordsRequest;
use BADDIServices\SourceeApp\Entities\Alert;
use Illuminate\Validation\ValidationException;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class SaveKeywordsController extends DashboardController
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        
        $this->userService = $userService;
    }

    public function __invoke(KeywordsRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->userService->update($this->user, $request->input());

            DB::commit();

            return redirect()->route('dashboard.keywords')
                ->with(
                    'alert', 
                    new Alert('Your keywords has been saved successfully', 'success')
                );
        } catch (ValidationException $ex){
            return redirect()->route('dashboard.keywords')
                ->withErrors($ex->errors)
                ->withInput();
        } catch (Throwable $ex){
            DB::rollBack();

            return redirect()->route('dashboard.keywords')
                ->with(
                    'alert', 
                    new Alert('An error occurred while saving your keywords!')
                )
                ->withInput();
        }
    }
}