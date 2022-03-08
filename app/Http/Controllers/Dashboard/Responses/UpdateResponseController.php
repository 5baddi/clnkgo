<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use BADDIServices\SourceeApp\Entities\Alert;
use Illuminate\Validation\ValidationException;
use BADDIServices\SourceeApp\Models\SavedResponse;
use BADDIServices\SourceeApp\Services\SavedResponseService;
use BADDIServices\SourceeApp\Http\Requests\SaveResponseRequest;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class UpdateResponseController extends DashboardController
{
    /** @var SavedResponseService */
    private $savedResponseService;

    public function __construct(SavedResponseService $savedResponseService)
    {
        parent::__construct();

        $this->savedResponseService = $savedResponseService;
    }
    
    public function __invoke(string $id, SaveResponseRequest $request)
    {
        $response = $this->savedResponseService->findById($id);
        abort_unless($response instanceof SavedResponse, Response::HTTP_NOT_FOUND);

        try {
            DB::beginTransaction();

            $this->savedResponseService->update($response, $request->input());

            DB::commit();

            return redirect()->route('dashboard.responses')
                ->with(
                    'alert', 
                    new Alert('Your canned response has been updated successfully', 'success')
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
                    new Alert('An error occurred while updating canned response!')
                )
                ->withInput();
        }
    }
}