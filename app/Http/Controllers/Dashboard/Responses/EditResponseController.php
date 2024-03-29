<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses;

use Illuminate\Http\Response;
use BADDIServices\ClnkGO\App;
use BADDIServices\ClnkGO\Models\Pack;
use BADDIServices\ClnkGO\Models\SavedResponse;
use BADDIServices\ClnkGO\Services\SavedResponseService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

class EditResponseController extends DashboardController
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

        return $this->render('dashboard.responses.edit', [
            'title'     => 'Edit Canned Response',
            'count'     => $this->savedResponseService->count($this->user),
            'response'  => $response,
            'max'       => collect($this->pack->features ?? [])->where('key', Pack::CANNED_RESPONSES)->first()['limit'] ?? App::MAX_CANNED_RESPONSES
        ]);
    }
}