<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use BADDIServices\Framework\Services\Service;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Repositories\CPALeadTrackingRepository;

class CPALeadTrackingService extends Service
{
    public function __construct(
        private CPALeadTrackingRepository $CPALeadTrackingRepository
    ) {
        $this->repository = $CPALeadTrackingRepository;
    }

    public function findByEmail(string $email): ?CPALeadTracking
    {
        return $this->repository->first([CPALeadTracking::EMAIL_COLUMN => $email]);
    }

    public function updateOrCreate(array $conditions, array $attributes): CPALeadTracking
    {
        return $this->repository->updateOrCreate($conditions, $attributes);
    }
}