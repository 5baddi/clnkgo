<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Repositories;

use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\Framework\Repositories\EloquentRepository;

class CPALeadTrackingRepository extends EloquentRepository
{
    /** @var CPALeadTracking */
    protected $model = CPALeadTracking::class;
}