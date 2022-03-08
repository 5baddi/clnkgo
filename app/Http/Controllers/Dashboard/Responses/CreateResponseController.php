<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses;

use Throwable;
use Illuminate\Support\Facades\DB;
use BADDIServices\SourceeApp\Entities\Alert;
use Illuminate\Validation\ValidationException;
use BADDIServices\SourceeApp\Services\SavedResponseService;
use BADDIServices\SourceeApp\Http\Requests\SaveResponseRequest;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class CreateResponseController extends DashboardController
{
    /** @var SavedResponseService */
    private $savedResponseService;

    public function __construct(SavedResponseService $savedResponseService)
    {
        parent::__construct();

        $this->savedResponseService = $savedResponseService;
    }
    
    public function __invoke(SaveResponseRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->savedResponseService->create($this->user, $request->input());

            DB::commit();

            return redirect()->route('dashboard.responses')
                ->with(
                    'alert', 
                    new Alert('Your canned response has been created successfully', 'success')
                );
        } catch (ValidationException $ex){
            return redirect()->route('dashboard.responses')
                ->withErrors($ex->errors)
                ->withInput();
        } catch (Throwable $ex){
            DB::rollBack();

            return redirect()->route('dashboard.responses')
                ->with(
                    'alert', 
                    new Alert('An error occurred while creating canned response!')
                )
                ->withInput();
        }
    }
}