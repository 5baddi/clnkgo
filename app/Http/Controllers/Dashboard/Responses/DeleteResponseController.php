<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\Entities\Alert;
use BADDIServices\ClnkGO\Models\SavedResponse;
use BADDIServices\ClnkGO\Services\SavedResponseService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

class DeleteResponseController extends DashboardController
{
    /** @var SavedResponseService */
    private $savedResponseService;

    public function __construct(SavedResponseService $savedResponseService)
    {
        parent::__construct();

        $this->savedResponseService = $savedResponseService;
    }
    
    public function __invoke(string $id)
    {
        $response = $this->savedResponseService->findById($id);
        abort_unless($response instanceof SavedResponse, Response::HTTP_NOT_FOUND);

        try {
            DB::beginTransaction();

            $this->savedResponseService->delete($response);

            DB::commit();

            return redirect()->route('dashboard.responses')
                ->with(
                    'alert', 
                    new Alert('Your canned response has been deleted successfully', 'success')
                );
        } catch (Throwable $ex){
            DB::rollBack();

            return redirect()->route('dashboard.responses')
                ->with(
                    'alert', 
                    new Alert('An error occurred while deleting canned response!')
                )
                ->withInput();
        }
    }
}