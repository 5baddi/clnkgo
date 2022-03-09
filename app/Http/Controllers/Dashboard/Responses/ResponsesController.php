<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses;

use BADDIServices\SourceeApp\App;
use App\Http\Requests\PaginationRequest;
use BADDIServices\SourceeApp\Models\Pack;
use BADDIServices\SourceeApp\Services\SavedResponseService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class ResponsesController extends DashboardController
{
    /** @var SavedResponseService */
    private $savedResponseService;

    public function __construct(SavedResponseService $savedResponseService)
    {
        parent::__construct();

        $this->savedResponseService = $savedResponseService;
    }

    public function __invoke(PaginationRequest $request)
    {
        return view('dashboard.responses.index', [
            'title'     => 'Canned Responses',
            'responses' => $this->savedResponseService->paginate($this->user, $request->query('page')),
            'max'       => collect($this->pack->features)->where('key', Pack::CANNED_RESPONSES)->first()['limit'] ?? App::MAX_CANNED_RESPONSES
        ]);
    }
}