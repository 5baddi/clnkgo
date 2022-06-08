<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses;

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Models\Pack;
use BADDIServices\SourceeApp\Services\SavedResponseService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class NewResponseController extends DashboardController
{
    /** @var SavedResponseService */
    private $savedResponseService;

    public function __construct(SavedResponseService $savedResponseService)
    {
        parent::__construct();

        $this->savedResponseService = $savedResponseService;
    }
    
    public function __invoke()
    {
        return $this->render('dashboard.responses.new', [
            'title'     => 'New Canned Response',
            'count'     => $this->savedResponseService->count($this->user),
            'max'       => collect($this->pack->features ?? [])->where('key', Pack::CANNED_RESPONSES)->first()['limit'] ?? App::MAX_CANNED_RESPONSES
        ]);
    }
}