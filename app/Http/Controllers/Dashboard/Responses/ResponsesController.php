<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses;

use BADDIServices\ClnkGO\App;
use App\Http\Requests\PaginationRequest;
use BADDIServices\ClnkGO\Models\Pack;
use BADDIServices\ClnkGO\Services\SavedResponseService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

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
        return $this->render('dashboard.responses.index', [
            'title'     => 'Templates',
            'responses' => $this->savedResponseService->paginate($this->user, $request->query('page')),
            'max'       => collect($this->pack->features ?? [])->where('key', Pack::CANNED_RESPONSES)->first()['limit'] ?? App::MAX_CANNED_RESPONSES
        ]);
    }
}