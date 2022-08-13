<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use BADDIServices\ClnkGO\Models\CPALeadTracking;
use BADDIServices\ClnkGO\Repositories\CPALeadTrackingRepository;

class CPALeadTrackingService extends Service
{
    /** @var CPALeadTrackingRepository */
    private $CPALeadTrackingRepository;

    public function __construct(CPALeadTrackingRepository $CPALeadTrackingRepository)
    {
        $this->CPALeadTrackingRepository = $CPALeadTrackingRepository;
    }

    public function findById(string $id): ?CPALeadTracking
    {
        return $this->CPALeadTrackingRepository->findById($id);
    }

    public function findByEmail(string $email): ?CPALeadTracking
    {
        return $this->CPALeadTrackingRepository->findByEmail($email);
    }

    public function save(array $attributes): CPALeadTracking
    {
        $filteredAttributes = collect($attributes)
            ->filter(function ($value) {
                return $value !== null;
            })
            ->only([
                CPALeadTracking::ID_COLUMN,
                CPALeadTracking::EMAIL_COLUMN,
                CPALeadTracking::SENT_AT_COLUMN,
            ]);

        return $this->CPALeadTrackingRepository->save($filteredAttributes->toArray());
    }
}