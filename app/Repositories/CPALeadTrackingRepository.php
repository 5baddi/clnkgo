<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;

class CPALeadTrackingRepository
{
    public function all(): Collection
    {
        return CPALeadTracking::query()
            ->get();
    }

    public function findById(string $id): ?CPALeadTracking
    {
        return CPALeadTracking::query()
            ->find($id);
    }
    
    public function findByEmail(string $email): ?CPALeadTracking
    {
        return CPALeadTracking::query()
            ->where([CPALeadTracking::EMAIL_COLUMN => $email])
            ->first();
    }

    public function create(array $attributes): CPALeadTracking
    {
        return CPALeadTracking::query()
            ->create($attributes);
    }
    
    public function save(array $attributes): CPALeadTracking
    {
        return CPALeadTracking::query()
            ->updateOrCreate(
                [
                    CPALeadTracking::EMAIL_COLUMN => $attributes[CPALeadTracking::EMAIL_COLUMN]
                ],
                Arr::except($attributes, [CPALeadTracking::EMAIL_COLUMN])
            );
    }

    public function delete(string $id): bool
    {
        return CPALeadTracking::query()
            ->find($id)
            ->delete();
    }
}